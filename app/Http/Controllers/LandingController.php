<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Enums\CourseMode;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Default menampilkan kursus online
        $courses = Course::with(['owner', 'instructor'])
            ->withCount('enrollments')
            ->online()
            ->latest()
            ->get();
        
        // Hitung total kursus untuk badge
        $totalCourses = Course::count();
        
        return view('landing.index', compact('courses', 'totalCourses'));
    }

    /**
     * Display the topics detail page.
     */
    /**
     * Display the main page with all content combined.
     */
    public function main()
    {
        return view('landing.main2');
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
