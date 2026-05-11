@extends('layouts.app')

@section('title', 'Lives - PikFreshFood')

@section('styles')
<style>
    .live-page {
        width: min(980px, 100%);
        margin: 0 auto;
    }
    .live-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
    }
    .live-head h1 {
        margin: 0;
        font-size: 1.5rem;
        color: var(--text-color);
    }
    .live-head p {
        margin: 4px 0 0;
        color: var(--muted-color);
        font-size: 0.92rem;
    }
    .live-feed-wrap {
        position: relative;
    }
    .live-feed {
        height: calc(100vh - 240px);
        min-height: 460px;
        overflow: hidden;
        border-radius: 20px;
        border: 1px solid var(--border-color);
        background: #050505;
        position: relative;
        touch-action: pan-y;
    }
    .live-track {
        height: 100%;
        width: 100%;
        transition: transform 0.28s ease;
    }
    .live-card {
        position: relative;
        height: 100%;
        min-height: 460px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        background: #000;
    }
    .live-card video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        background: #000;
    }
    .live-overlay {
        position: absolute;
        left: 16px;
        right: 16px;
        bottom: 16px;
        color: #fff;
        display: grid;
        gap: 6px;
        padding: 12px;
        border-radius: 14px;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.08) 0%, rgba(0, 0, 0, 0.72) 100%);
    }
    .live-overlay strong {
        font-size: 1rem;
    }
    .live-overlay small {
        color: rgba(255, 255, 255, 0.82);
    }
    .live-arrow {
        position: absolute;
        right: 12px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid var(--border-color);
        background: #fff;
        color: #111;
        font-size: 1.2rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.2);
        z-index: 8;
    }
    .live-arrow.up { top: 42%; }
    .live-arrow.down { top: 52%; }
    .live-arrow svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2.1;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .live-audio-btn {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.5);
        background: rgba(0, 0, 0, 0.45);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 8;
    }
    .live-audio-btn svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .live-empty {
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 22px;
        text-align: center;
        color: var(--muted-color);
        background: var(--bottom-sheet-bg);
    }
    @media (max-width: 1023px) {
        .live-feed,
        .live-card {
            height: calc(100vh - 190px);
            min-height: 420px;
            border-radius: 14px;
        }
        .live-arrow {
            width: 36px;
            height: 36px;
            right: 8px;
        }
        .live-arrow.up { top: 40%; }
        .live-arrow.down { top: 50%; }
    }
</style>
@endsection

@section('content')
<div class="live-page">
    <div class="live-head">
        <div>
            <h1>Lives</h1>
            <p>Swipe up/down to watch fresh live videos from nearby vendors.</p>
        </div>
    </div>

    @if($videos->isEmpty())
        <div class="live-empty">No live videos yet. Check back soon.</div>
    @else
        <div class="live-feed-wrap">
            <button type="button" class="live-arrow up" id="livePrevBtn" aria-label="Previous live video">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 14 6-6 6 6"></path></svg>
            </button>
            <button type="button" class="live-arrow down" id="liveNextBtn" aria-label="Next live video">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 10 6 6 6-6"></path></svg>
            </button>
            <button type="button" class="live-audio-btn" id="liveAudioBtn" aria-label="Toggle audio">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 9v6h4l5 4V5L9 9H5Z"></path><path d="M18 9a4 4 0 0 1 0 6"></path></svg>
            </button>

            <div class="live-feed" id="liveFeed">
                <div class="live-track" id="liveTrack">
                @foreach($videos as $video)
                    <article class="live-card">
                        <video
                            src="{{ \App\Support\PublicStorage::url($video->video_path) }}"
                            preload="metadata"
                            loop
                            playsinline
                        ></video>
                        <div class="live-overlay">
                            <strong>{{ $video->title ?: 'Live from ' . $video->vendor->shop_name }}</strong>
                            <small>{{ $video->vendor->shop_name }} • {{ $video->duration_seconds }}s</small>
                        </div>
                    </article>
                @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const feed = document.getElementById('liveFeed');
        const track = document.getElementById('liveTrack');
        if (!feed || !track) {
            return;
        }

        const cards = Array.from(feed.querySelectorAll('.live-card'));
        const videos = Array.from(feed.querySelectorAll('video'));
        const prevButton = document.getElementById('livePrevBtn');
        const nextButton = document.getElementById('liveNextBtn');
        const audioButton = document.getElementById('liveAudioBtn');
        let currentIndex = 0;
        let isTransitioning = false;
        let touchStartY = null;
        let audioEnabled = true;

        const syncPlayback = function () {
            videos.forEach(function (video, index) {
                if (index === currentIndex) {
                    video.muted = !audioEnabled;
                    video.volume = 1;
                    video.play().catch(function () {});
                } else {
                    video.pause();
                    video.currentTime = 0;
                    video.muted = true;
                }
            });
        };

        const renderAudioIcon = function () {
            if (!audioButton) {
                return;
            }

            if (audioEnabled) {
                audioButton.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 9v6h4l5 4V5L9 9H5Z"></path><path d="M18 9a4 4 0 0 1 0 6"></path></svg>';
            } else {
                audioButton.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 9v6h4l5 4V5L9 9H5Z"></path><path d="m19 9-6 6"></path><path d="m13 9 6 6"></path></svg>';
            }
        };

        const goTo = function (index) {
            if (cards.length === 0) {
                return;
            }

            const nextIndex = Math.max(0, Math.min(index, cards.length - 1));
            if (nextIndex === currentIndex || isTransitioning) {
                return;
            }

            isTransitioning = true;
            currentIndex = nextIndex;
            track.style.transform = 'translateY(-' + (currentIndex * 100) + '%)';
            syncPlayback();

            window.setTimeout(function () {
                isTransitioning = false;
            }, 300);
        };

        if (prevButton) {
            prevButton.addEventListener('click', function () {
                goTo(currentIndex - 1);
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', function () {
                goTo(currentIndex + 1);
            });
        }

        if (audioButton) {
            audioButton.addEventListener('click', function () {
                audioEnabled = !audioEnabled;
                renderAudioIcon();
                syncPlayback();
            });
        }

        feed.addEventListener('wheel', function (event) {
            if (Math.abs(event.deltaY) < 20) {
                return;
            }

            event.preventDefault();
            if (event.deltaY > 0) {
                goTo(currentIndex + 1);
            } else {
                goTo(currentIndex - 1);
            }
        }, { passive: false });

        feed.addEventListener('touchstart', function (event) {
            touchStartY = event.touches[0].clientY;
        }, { passive: true });

        feed.addEventListener('touchend', function (event) {
            if (touchStartY === null) {
                return;
            }

            const endY = event.changedTouches[0].clientY;
            const deltaY = touchStartY - endY;
            touchStartY = null;

            if (Math.abs(deltaY) < 45) {
                return;
            }

            if (deltaY > 0) {
                goTo(currentIndex + 1);
            } else {
                goTo(currentIndex - 1);
            }
        }, { passive: true });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowDown') {
                goTo(currentIndex + 1);
            } else if (event.key === 'ArrowUp') {
                goTo(currentIndex - 1);
            }
        });

        track.style.transform = 'translateY(0%)';
        renderAudioIcon();
        syncPlayback();
    })();
</script>
@endsection
