<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        // Lấy tất cả interns với university_id, department và tasks
        $interns = \App\Models\Intern::select('interns.*', 'universities.name as university_name')
            ->leftJoin('universities', 'interns.university_id', '=', 'universities.university_id')
            ->with(['tasks', 'department'])
            ->get();
        
        // Tính điểm trung bình cho mỗi intern
        $internEvaluations = [];
        $universityStats = [];
        $universityScores = [];
        $positionScores = [];
        $departmentScores = []; // Thêm mảng để lưu điểm theo phòng ban
        
        foreach ($interns as $intern) {
            $totalScore = 0;
            $taskCount = 0;
            
            // Tính điểm trung bình cho intern
            foreach ($intern->tasks as $task) {
                if ($task->evaluation) {
                    $score = $this->convertEvaluationToScore($task->evaluation);
                    $totalScore += $score;
                    $taskCount++;
                }
            }
            
            // Chỉ thêm vào đánh giá nếu có task được đánh giá
            if ($taskCount > 0) {
                $averageScore = $totalScore / $taskCount;
                $rating = $this->getRating($averageScore);
                
                $internEvaluations[] = [
                    'name' => $intern->fullname,
                    'university' => $intern->university_name ?? 'Chưa xác định',
                    'task_count' => $taskCount,
                    'average_score' => $averageScore,
                    'rating' => $rating
                ];
                
                // Thống kê theo trường đại học
                $universityName = $intern->university_name ?? 'Chưa xác định';
                if (!isset($universityStats[$universityName])) {
                    $universityStats[$universityName] = 0;
                    $universityScores[$universityName] = [
                        'total_score' => 0,
                        'count' => 0
                    ];
                }
                $universityStats[$universityName]++;
                $universityScores[$universityName]['total_score'] += $averageScore;
                $universityScores[$universityName]['count']++;

                // Thống kê theo vị trí
                $position = $intern->position ?? 'Chưa xác định';
                if (!isset($positionScores[$position])) {
                    $positionScores[$position] = [
                        'total_score' => 0,
                        'count' => 0
                    ];
                }
                $positionScores[$position]['total_score'] += $averageScore;
                $positionScores[$position]['count']++;

                // Thống kê theo phòng ban
                $department = $intern->department ? $intern->department->name : 'Chưa xác định';
                if (!isset($departmentScores[$department])) {
                    $departmentScores[$department] = [
                        'total_score' => 0,
                        'count' => 0
                    ];
                }
                $departmentScores[$department]['total_score'] += $averageScore;
                $departmentScores[$department]['count']++;
            }
        }

        // Chuẩn bị dữ liệu cho biểu đồ đánh giá
        $chartData = $this->prepareChartData($internEvaluations);
        
        // Chuẩn bị dữ liệu cho biểu đồ cột trường đại học
        $universityChartData = [
            'labels' => array_keys($universityStats),
            'data' => array_values($universityStats),
            'colors' => $this->generateColors(count($universityStats))
        ];

        // Chuẩn bị dữ liệu cho biểu đồ điểm trung bình theo trường
        $universityScoreData = [
            'labels' => [],
            'data' => [],
            'colors' => []
        ];

        foreach ($universityScores as $university => $data) {
            if ($data['count'] > 0) {
                $averageScore = $data['total_score'] / $data['count'];
                $universityScoreData['labels'][] = $university;
                $universityScoreData['data'][] = round($averageScore, 2);
                $universityScoreData['colors'][] = $this->generateColors(1)[0];
            }
        }

        // Chuẩn bị dữ liệu cho biểu đồ radar theo vị trí
        $positionChartData = [
            'labels' => [],
            'data' => []
        ];

        foreach ($positionScores as $position => $data) {
            if ($data['count'] > 0) {
                $averageScore = round($data['total_score'] / $data['count'], 2);
                $positionChartData['labels'][] = $position;
                $positionChartData['data'][] = $averageScore;
            }
        }

        // Chuẩn bị dữ liệu cho biểu đồ điểm trung bình theo phòng ban
        $departmentScoreData = [
            'labels' => [],
            'data' => []
        ];

        foreach ($departmentScores as $department => $data) {
            if ($data['count'] > 0) {
                $averageScore = round($data['total_score'] / $data['count'], 2);
                $departmentScoreData['labels'][] = $department;
                $departmentScoreData['data'][] = $averageScore;
            }
        }

        // Chuẩn bị dữ liệu cho biểu đồ phân bố xếp loại theo trường
        $universityRatingData = [
            'labels' => [],
            'excellent' => [],
            'good' => [],
            'average' => []
        ];

        // Tạo mảng tạm để tính toán
        $tempData = [];
        foreach ($interns as $intern) {
            $universityName = $intern->university_name ?? 'Chưa xác định';
            if (!isset($tempData[$universityName])) {
                $tempData[$universityName] = [
                    'excellent' => 0,
                    'good' => 0,
                    'average' => 0
                ];
            }

            $totalScore = 0;
            $taskCount = 0;
            foreach ($intern->tasks as $task) {
                if ($task->evaluation) {
                    $score = $this->convertEvaluationToScore($task->evaluation);
                    $totalScore += $score;
                    $taskCount++;
                }
            }

            if ($taskCount > 0) {
                $averageScore = $totalScore / $taskCount;
                $rating = $this->getRating($averageScore);
                
                switch ($rating) {
                    case 'Xuất sắc':
                        $tempData[$universityName]['excellent']++;
                        break;
                    case 'Tốt':
                        $tempData[$universityName]['good']++;
                        break;
                    case 'Trung bình':
                        $tempData[$universityName]['average']++;
                        break;
                }
            }
        }

        // Chuyển đổi dữ liệu sang định dạng cho biểu đồ
        $universityRatingData['labels'] = array_keys($tempData);
        foreach ($tempData as $data) {
            $universityRatingData['excellent'][] = $data['excellent'];
            $universityRatingData['good'][] = $data['good'];
            $universityRatingData['average'][] = $data['average'];
        }

        return view('admin.evaluations.index', compact(
            'internEvaluations',
            'chartData',
            'universityChartData',
            'universityScoreData',
            'positionChartData',
            'departmentScoreData',
            'universityRatingData'
        ));
    }

    private function convertEvaluationToScore($evaluation)
    {
        return match($evaluation) {
            'Rất tốt' => 10,
            'Tốt' => 8,
            'Trung bình' => 6,
            'Kém' => 4,
            default => 0
        };
    }

    private function getRating($score)
    {
        if ($score >= 9) return 'Xuất sắc';
        if ($score >= 8) return 'Tốt';
        if ($score >= 5) return 'Trung bình';
        return 'Kém';
    }

    private function prepareChartData($evaluations)
    {
        $ratings = [
            'Xuất sắc' => 0,
            'Tốt' => 0,
            'Trung bình' => 0,
            'Kém' => 0
        ];

        foreach ($evaluations as $evaluation) {
            $ratings[$evaluation['rating']]++;
        }

        return [
            'labels' => array_keys($ratings),
            'data' => array_values($ratings),
            'colors' => ['#28a745', '#20c997', '#ffc107', '#dc3545']
        ];
    }

    // Thêm hàm tạo màu ngẫu nhiên cho biểu đồ
    private function generateColors($count)
    {
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }
        return $colors;
    }
} 