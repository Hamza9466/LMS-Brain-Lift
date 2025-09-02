@extends('admin.layouts.main')

@section('content')
<div class="dashboard-content">
    <div class="container">
        <h4 class="dashboard-title">Dashboard</h4>

        <div class="dashboard-info">
            <div class="row gy-2 gy-sm-6">

                {{-- Enrolled Courses --}}
                <div class="col-md-4 col-sm-6">
                    <div class="dashboard-info__card">
                        <div class="dashboard-info__card-box">
                            <div class="dashboard-info__card-icon icon-color-01">
                                <i class="edumi edumi-open-book"></i>
                            </div>
                            <div class="dashboard-info__card-content">
                                <div class="dashboard-info__card-value">{{ number_format($enrolledCoursesCount) }}</div>
                                <div class="dashboard-info__card-heading">Enrolled Courses</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Active Courses --}}
                <div class="col-md-4 col-sm-6">
                    <div class="dashboard-info__card">
                        <div class="dashboard-info__card-box">
                            <div class="dashboard-info__card-icon icon-color-02">
                                <i class="edumi edumi-streaming"></i>
                            </div>
                            <div class="dashboard-info__card-content">
                                <div class="dashboard-info__card-value">{{ number_format($activeCoursesCount) }}</div>
                                <div class="dashboard-info__card-heading">Active Courses</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Completed Courses --}}
                <div class="col-md-4 col-sm-6">
                    <div class="dashboard-info__card">
                        <div class="dashboard-info__card-box">
                            <div class="dashboard-info__card-icon icon-color-03">
                                <i class="edumi edumi-correct"></i>
                            </div>
                            <div class="dashboard-info__card-content">
                                <div class="dashboard-info__card-value">{{ number_format($completedCoursesCount) }}</div>
                                <div class="dashboard-info__card-heading">Completed Courses</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Students (admin only) --}}
                @if($role === 'admin')
                <div class="col-md-4 col-sm-6">
                    <div class="dashboard-info__card">
                        <div class="dashboard-info__card-box">
                            <div class="dashboard-info__card-icon icon-color-04">
                                <i class="edumi edumi-group"></i>
                            </div>
                            <div class="dashboard-info__card-content">
                                <div class="dashboard-info__card-value">{{ number_format($totalStudents) }}</div>
                                <div class="dashboard-info__card-heading">Total Students</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Total Courses --}}
                <div class="col-md-4 col-sm-6">
                    <div class="dashboard-info__card">
                        <div class="dashboard-info__card-box">
                            <div class="dashboard-info__card-icon icon-color-05">
                                <i class="edumi edumi-user-support"></i>
                            </div>
                            <div class="dashboard-info__card-content">
                                <div class="dashboard-info__card-value">{{ number_format($totalCourses) }}</div>
                                <div class="dashboard-info__card-heading">Total Courses</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Earnings (admin/teacher) --}}
                @if($role === 'admin')
                <div class="col-md-4 col-sm-6">
                    <div class="dashboard-info__card">
                        <div class="dashboard-info__card-box">
                            <div class="dashboard-info__card-icon icon-color-06">
                                <i class="edumi edumi-coin"></i>
                            </div>
                            <div class="dashboard-info__card-content">
                                <div class="dashboard-info__card-value">
                                  <span class="sale-price">{{ $currencySymbol }}{{ number_format($earnings) }}</span>

                                </div>
                                <div class="dashboard-info__card-heading">Earnings</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div> <!-- row -->
        </div> <!-- dashboard-info -->
    </div> <!-- container -->
</div> <!-- dashboard-content -->
@endsection
