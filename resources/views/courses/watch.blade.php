<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0">
            Viéndo: {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow border-0 bg-dark text-white rounded-4 overflow-hidden">
                    <div class="card-body p-0 ratio ratio-16x9">
                        <!-- El reproductor de video. Simulamos con un iframe genérico o tag video en base la ruta de BDD -->
                        @if (Str::startsWith($course->video_path, 'http'))
                            <iframe src="{{ $course->formatted_video_url }}" class="w-100 h-100" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                            <video controls controlsList="nodownload" class="w-100 h-100 bg-black">
                                <source src="{{ asset('storage/' . $course->video_path) }}" type="video/mp4">
                                Tu navegador no soporta el reproductor de video.
                            </video>
                        @endif
                    </div>
                    <div class="p-4 bg-dark">
                        <h4 class="mb-1 fw-bold">{{ $course->title }}</h4>
                        <p class="mb-0 text-white-50"><i class="bi bi-person-fill"></i> Instructor: {{ $course->author->name ?? 'N/A' }}</p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-secondary rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> Volver a Detalles
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
