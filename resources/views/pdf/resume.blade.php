<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Resume</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.5;
            background-color: #f8fafc;
        }

        .header-bg {
            background-color: #333;
            /* Dark background */
            color: #fff;
            padding: 40px 50px;
            text-align: left;
        }

        .header-name {
            font-size: 32px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-title {
            font-size: 16px;
            color: #ccc;
            margin-top: 5px;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-info {
            font-size: 12px;
            color: #eee;
        }

        .header-info span {
            margin-right: 15px;
        }

        .container {
            padding: 40px 50px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
            color: #555;
            letter-spacing: 1px;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .content-item {
            margin-bottom: 15px;
        }

        .content-title {
            font-weight: bold;
            font-size: 14px;
            color: #000;
        }

        .content-subtitle {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            font-style: italic;
        }

        .content-text {
            font-size: 12px;
            color: #444;
            text-align: justify;
        }

        /* Skills Tags */
        .skills-container {
            margin-bottom: 5px;
        }

        .skill-tag {
            display: inline-block;
            background-color: #e2e8f0;
            color: #334155;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .skill-level {
            font-weight: normal;
            opacity: 0.7;
            font-size: 9px;
            margin-left: 3px;
        }

        /* Two Column Layout for Body */
        .row {
            width: 100%;
        }

        .col-left {
            width: 65%;
            float: left;
            padding-right: 5%;
        }

        .col-right {
            width: 30%;
            float: left;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .footer {
            position: fixed;
            bottom: 30px;
            left: 50px;
            right: 50px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .badge-pill {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            background-color: #3b82f6;
            color: white;
            margin-left: 5px;
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <div class="header-bg">
        <div style="float: left; width: 100px; margin-right: 20px;">
             @if(isset($avatarBase64))
                <img src="{{ $avatarBase64 }}" style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid #fff; object-fit: cover;">
             @endif
        </div>
        <div style="margin-left: 120px;">
            <h1 class="header-name">{{ $user->name }}</h1>
            <div class="header-title">{{ $user->major }} Student @ {{ $user->university }}</div>
            
            <div class="header-info">
                <span>{{ $user->email }}</span>
                @if($user->phone) <span>• {{ $user->phone }}</span> @endif
                @if($user->location) <span>• {{ $user->location }}</span> @endif
                @if($user->github_username) <span>• github.com/{{ $user->github_username }}</span> @endif
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- Main Content -->
    <div class="container clearfix">

        <!-- Left Column -->
        <div class="col-left">
            @if($user->bio)
                <div class="section">
                    <div class="section-title">About Me</div>
                    <div class="content-text">{!! nl2br(preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', e($user->bio))) !!}</div>
                </div>
            @endif

            @if($user->projects->isNotEmpty())
                <div class="section">
                    <div class="section-title">Project Experience</div>
                    @foreach($user->projects as $project)
                        <div class="content-item">
                            <div class="content-title">
                                {{ $project->title }}
                                @if($project->status == 'completed')
                                    <span style="font-weight: normal; font-size: 10px; color: #10b981;">(Completed)</span>
                                @endif
                            </div>
                            <div class="content-subtitle">Role: {{ ucfirst($project->pivot->role) }}</div>
                            <p class="content-text">{!! nl2br(preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', e(Str::limit($project->description, 300)))) !!}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($user->ownedProjects->isNotEmpty())
                <div class="section">
                    <div class="section-title">Leadership & Management</div>
                    @foreach($user->ownedProjects as $project)
                        <div class="content-item">
                            <div class="content-title">{{ $project->title }}</div>
                            <div class="content-subtitle">Project Owner / Lead</div>
                            <p class="content-text">{!! nl2br(preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', e(Str::limit($project->description, 300)))) !!}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-right">
            <div class="section">
                <div class="section-title">Education</div>
                <div class="content-item">
                    <div class="content-title">{{ $user->university }}</div>
                    <div class="content-subtitle">{{ $user->major }}</div>
                    <div class="content-text">Graduating in {{ $user->graduation_year }}</div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Skills & Expertise</div>

                @forelse($user->skills as $skill)
                    <div style="margin-bottom: 8px;">
                        <div style="font-size: 12px; font-weight: bold; margin-bottom: 2px;">{{ $skill->name }}</div>
                        <div style="background-color: #e2e8f0; height: 4px; width: 100%; border-radius: 2px;">
                            @php
                                $levelMap = ['beginner' => 25, 'intermediate' => 50, 'advanced' => 75, 'expert' => 100];
                                $width = $levelMap[$skill->pivot->proficiency_level] ?? 50;
                            @endphp
                            <div style="background-color: #333; height: 100%; width: {{ $width }}%; border-radius: 2px;">
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="content-text">No skills listed.</div>
                @endforelse
            </div>


        </div>

    </div>

    <!-- Footer -->
    <div class="footer">
        Generated by Swap Hub on {{ now()->format('F j, Y') }}
    </div>

</body>

</html>