<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBorrowRecordRequest;
use App\Http\Requests\UpdateBorrowRecordRequest;
use App\Models\BorrowRecord;

class BorrowRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $limit = request()->limit ?? 15;

        $borrowRecords = BorrowRecord::paginate($limit);
        return respondSuccess("Borrow records", $borrowRecords);
    }

    /**
     * Display the specified resource.
     */
    public function show(BorrowRecord $borrowRecord)
    {
        return respondSuccess("Borrow record", $borrowRecord);
    }
}
