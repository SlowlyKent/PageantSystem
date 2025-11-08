<?php

namespace App\Controllers\Role;

use App\Controllers\BaseController;
use App\Models\RoundModel;
use App\Models\RoundSegmentModel;
use App\Models\CriteriaModel;

class RoundsCriteriaController extends BaseController
{
    protected $roundModel;
    protected $segmentModel;
    protected $criteriaModel;

    public function __construct()
    {
        $this->roundModel = new RoundModel();
        $this->segmentModel = new RoundSegmentModel();
        $this->criteriaModel = new CriteriaModel();
    }

    /**
     * Display all rounds
     */
    public function index()
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        $rounds = $this->roundModel->getAllWithSegmentCount();

        $data = [
            'title' => 'Rounds & Criteria',
            'rounds' => $rounds,
        ];

        return view('admin/rounds_criteria/index', $data);
    }

    /**
     * Show form to create new round
     */
    public function create()
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        $nextRoundNumber = $this->roundModel->getNextRoundNumber();

        $data = [
            'title' => 'Add New Round',
            'next_round_number' => $nextRoundNumber,
        ];

        return view('admin/rounds_criteria/create', $data);
    }

    /**
     * Store new round with segments and criteria
     */
    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Save round
            $roundData = [
                'round_number'   => $this->request->getPost('round_number'),
                'round_name'     => $this->request->getPost('round_name'),
                'description'    => $this->request->getPost('round_description'),
                'segment_count'  => $this->request->getPost('segment_count'),
                'status'         => 'active',
            ];

            $roundId = $this->roundModel->insert($roundData);

            if (!$roundId) {
                throw new \Exception('Failed to create round');
            }

            $segmentCount = (int)$this->request->getPost('segment_count');

            // Save segments
            for ($i = 1; $i <= $segmentCount; $i++) {
                $segmentData = [
                    'round_id'          => $roundId,
                    'segment_number'    => $i,
                    'segment_name'      => $this->request->getPost("segment{$i}_name"),
                    'description'       => $this->request->getPost("segment{$i}_description"),
                    'weight_percentage' => $segmentCount == 1 ? 100.00 : 50.00,
                ];

                $segmentId = $this->segmentModel->insert($segmentData);

                if (!$segmentId) {
                    throw new \Exception("Failed to create segment {$i}");
                }

                // Save criteria for this segment
                $criteriaNames = $this->request->getPost("segment{$i}_criteria_name");
                $criteriaPercentages = $this->request->getPost("segment{$i}_criteria_percentage");
                $criteriaMaxScores = $this->request->getPost("segment{$i}_criteria_max_score");
                $criteriaDescriptions = $this->request->getPost("segment{$i}_criteria_description");

                if ($criteriaNames && is_array($criteriaNames)) {
                    foreach ($criteriaNames as $index => $criteriaName) {
                        if (!empty($criteriaName)) {
                            $criteriaData = [
                                'segment_id'    => $segmentId,
                                'criteria_name' => $criteriaName,
                                'description'   => $criteriaDescriptions[$index] ?? '',
                                'max_score'     => $criteriaMaxScores[$index] ?? 100,
                                'percentage'    => $criteriaPercentages[$index] ?? 0,
                                'order'         => $index + 1,
                            ];

                            $this->criteriaModel->insert($criteriaData);
                        }
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            return redirect()->to(base_url('admin/rounds-criteria'))
                           ->with('success', 'Round created successfully with ' . $segmentCount . ' segment(s)!');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create round: ' . $e->getMessage());
        }
    }

    /**
     * View round details
     */
    public function view($id)
    {
        session()->set([
            'user_role' => 'admin',
            'user_name' => session()->get('user_name') ?? 'Admin User'
        ]);

        $round = $this->roundModel->getRoundWithDetails($id);

        if (!$round) {
            return redirect()->to(base_url('admin/rounds-criteria'))
                           ->with('error', 'Round not found.');
        }

        $data = [
            'title' => 'Round Details',
            'round' => $round,
        ];

        return view('admin/rounds_criteria/view', $data);
    }

    /**
     * Delete round
     */
    public function delete($id)
    {
        $round = $this->roundModel->find($id);

        if (!$round) {
            return redirect()->to(base_url('admin/rounds-criteria'))
                           ->with('error', 'Round not found.');
        }

        // Delete round (segments and criteria will cascade)
        if ($this->roundModel->delete($id)) {
            return redirect()->to(base_url('admin/rounds-criteria'))
                           ->with('success', 'Round deleted successfully!');
        } else {
            return redirect()->back()
                           ->with('error', 'Failed to delete round.');
        }
    }
}
