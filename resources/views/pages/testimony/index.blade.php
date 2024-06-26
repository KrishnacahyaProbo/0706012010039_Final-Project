@section('title', 'Testimoni')

<x-app-layout>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-grid gap-3">
        <div>
            @section('page_title', 'Testimoni')
        </div>

        @if ($testimony->isEmpty())
            <div class="alert alert-secondary d-grid gap-3 text-center" role="alert">
                <i class="bi bi-chat-left-text-fill display-1"></i>
                <span>Belum terdapat testimoni pada vendor.</span>
            </div>
        @endif

        @if ($testimony->count())
            @foreach ($testimony as $item)
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <img src={{ $item->customer?->profile_photo_url }} alt="" width="48">
                                <span>{{ $item?->customer?->name }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-star-fill text-warning fs-4"></i>
                                <h4 class="mb-0">{{ $item->rating }}/5</h4>
                            </div>
                            <span>
                                <pre class="mb-0">{{ $item->description ?? '-' }}</pre>
                            </span>

                            @if ($item->testimony_photo)
                                <div>
                                    <a href="/assets/image/testimony_photo/{{ $item->testimony_photo }}" target="_blank"
                                        rel="noopener noreferrer">
                                        <img src="/assets/image/testimony_photo/{{ $item->testimony_photo }}"
                                            alt="" class="rounded-1" width="196" loading="lazy">
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <small
                            class="text-secondary">{{ \Carbon\Carbon::parse($item->created_at)->locale('id_ID')->isoFormat('dddd, D MMMM YYYY HH:m:s') }}
                        </small>
                    </div>
                </div>
            @endforeach
        @endif
        <ul class="pagination justify-content-center">
            {{ $testimony->links() }}
        </ul>
    </div>
</x-app-layout>
