@extends('layouts.app')

@section('title', 'Edit Product - PikFreshFood')

@section('styles')
<style>
    .edit-product-container {
        max-width: 720px; margin: 30px auto; padding: 24px;
        background: var(--bottom-sheet-bg); border: 1px solid var(--border-color); border-radius: 18px;
    }
    .edit-product-container h1 { margin-bottom: 10px; color: var(--text-color); }
    .edit-product-container p { color: var(--muted-color); margin-bottom: 18px; }
    .edit-product-form { display: grid; gap: 12px; }
    .edit-product-form input,
    .edit-product-form textarea,
    .edit-product-form select {
        width: 100%; padding: 12px 14px; border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--surface-bg);
        color: var(--text-color);
    }
    .edit-product-form textarea { min-height: 110px; resize: vertical; }
    .edit-product-gallery {
        display: grid;
        gap: 14px;
        padding: 16px;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        background: color-mix(in srgb, var(--primary-color) 4%, white 96%);
    }
    .gallery-stage {
        position: relative;
        overflow: hidden;
        border-radius: 14px;
        background: var(--surface-alt);
        border: 1px solid var(--border-color);
        aspect-ratio: 16 / 10;
    }
    .gallery-track {
        display: flex;
        height: 100%;
        transition: transform 0.3s ease;
    }
    .gallery-slide {
        min-width: 100%;
        height: 100%;
        position: relative;
    }
    .gallery-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .gallery-slide-tag {
        position: absolute;
        left: 12px;
        top: 12px;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(0, 0, 0, 0.68);
        color: white;
        font-size: 0.8rem;
        font-weight: 700;
    }
    .gallery-empty {
        display: grid;
        place-items: center;
        height: 100%;
        color: var(--muted-color);
        padding: 18px;
        text-align: center;
    }
    .gallery-nav {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 10px;
        pointer-events: none;
    }
    .gallery-nav button {
        pointer-events: auto;
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.62);
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
    }
    .gallery-nav button[disabled] {
        opacity: 0.35;
        cursor: not-allowed;
    }
    .gallery-meta {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        color: var(--muted-color);
        font-size: 0.9rem;
        flex-wrap: wrap;
    }
    .gallery-thumbs {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(92px, 1fr));
        gap: 12px;
    }
    .gallery-thumb {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid transparent;
        background: var(--surface-alt);
        aspect-ratio: 1 / 1;
        cursor: pointer;
    }
    .gallery-thumb.is-active { border-color: var(--primary-color); }
    .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .gallery-thumb-remove {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: none;
        background: rgba(0, 0, 0, 0.72);
        color: white;
        font-size: 1rem;
        cursor: pointer;
    }
    .gallery-help {
        color: var(--muted-color);
        font-size: 0.88rem;
    }
    .gallery-upload {
        display: grid;
        gap: 8px;
    }
    .upload-progress {
        display: none;
        gap: 8px;
        padding: 12px 14px;
        border-radius: 10px;
        background: rgba(39, 174, 96, 0.08);
        border: 1px solid rgba(39, 174, 96, 0.18);
    }
    .upload-progress.is-visible { display: grid; }
    .upload-progress-meta {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        font-size: 0.9rem;
        color: var(--text-color);
    }
    .upload-progress-bar {
        width: 100%;
        height: 10px;
        border-radius: 999px;
        background: rgba(39, 174, 96, 0.14);
        overflow: hidden;
    }
    .upload-progress-fill {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, #27ae60 0%, #58d68d 100%);
        transition: width 0.18s ease;
    }
    .success-modal {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(10, 18, 14, 0.56);
        z-index: 1200;
    }
    .success-modal.is-visible { display: flex; }
    .success-modal-card {
        width: 100%;
        max-width: 360px;
        padding: 28px 24px;
        border-radius: 18px;
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.22);
        text-align: center;
    }
    .success-modal-badge {
        width: 62px;
        height: 62px;
        margin: 0 auto 16px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: rgba(39, 174, 96, 0.14);
        color: #27ae60;
    }
    .success-modal-card h2 {
        margin: 0 0 8px;
        color: var(--text-color);
    }
    .success-modal-card p {
        margin: 0;
        color: var(--muted-color);
    }
    .edit-product-check {
        display: flex; align-items: center; gap: 10px; color: var(--text-color); font-weight: 600;
    }
    .edit-product-check input[type="checkbox"] {
        width: auto;
        margin: 0;
        padding: 0;
        flex: 0 0 auto;
    }
    .edit-product-actions { display: flex; gap: 10px; }
    .edit-product-btn {
        min-height: 44px; padding: 0 16px; border-radius: 10px; text-decoration: none;
        border: 1px solid var(--border-color); font-weight: 700;
    }
    .edit-product-btn.primary { background: var(--primary-color); color: white; border-color: var(--primary-color); }
    .edit-product-btn.secondary { background: var(--surface-alt); color: var(--text-color); }
</style>
@endsection

@section('content')
<div class="edit-product-container">
    <h1>Edit Product</h1>
    <p>Update your product details, stock, availability, and product photo gallery.</p>

    <div class="upload-progress" id="editProductProgress">
        <div class="upload-progress-meta">
            <span id="editProductProgressLabel">Uploading changes...</span>
            <strong id="editProductProgressValue">0%</strong>
        </div>
        <div class="upload-progress-bar">
            <div class="upload-progress-fill" id="editProductProgressFill"></div>
        </div>
    </div>

    <form action="{{ route('vendor.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="edit-product-form" id="editProductForm">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="Product Name" required>
        <textarea name="description" placeholder="Description">{{ old('description', $product->description) }}</textarea>
        <input type="text" name="category" value="{{ old('category', $product->category) }}" placeholder="Category" required>
        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" placeholder="Price" required>
        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" placeholder="Stock Quantity" required>
        <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" placeholder="Unit" required>

        <div class="edit-product-gallery">
            <div class="gallery-stage">
                <div class="gallery-track" id="galleryTrack"></div>
                <div class="gallery-empty" id="galleryEmpty">No product photos selected yet.</div>
                <div class="gallery-nav">
                    <button type="button" id="galleryPrev" aria-label="Previous photo">&lsaquo;</button>
                    <button type="button" id="galleryNext" aria-label="Next photo">&rsaquo;</button>
                </div>
            </div>

            <div class="gallery-meta">
                <span id="galleryCounter">0 / 0 photos</span>
                <span>Upload up to 6 photos total. Remove any photo you no longer want.</span>
            </div>

            <div class="gallery-thumbs" id="galleryThumbs"></div>

            <div class="gallery-upload">
                <label for="productImages">Add or replace photos</label>
                <input type="file" id="productImages" name="images[]" accept="image/*" multiple>
                <div class="gallery-help">New photos are added to the gallery. Photos removed here will be deleted when you save changes.</div>
            </div>

            <div id="removedImagesContainer"></div>
        </div>

        <label class="edit-product-check">
            <input type="checkbox" name="is_available" value="1" {{ old('is_available', $product->is_available) ? 'checked' : '' }}>
            Product is available
        </label>

        <div class="edit-product-actions">
            <button type="submit" class="edit-product-btn primary">Save Changes</button>
            <a href="{{ route('vendor.products') }}" class="edit-product-btn secondary">Back</a>
        </div>
    </form>
</div>

<div class="success-modal" id="editProductSuccessModal" aria-hidden="true">
    <div class="success-modal-card">
        <div class="success-modal-badge">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="m5 12.5 4.2 4.2L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h2>Update Complete</h2>
        <p id="editProductModalMessage">Your product was updated successfully.</p>
    </div>
</div>
@endsection

@php
    $removedExistingImages = old('removed_existing_images', []);
    $visibleImagePaths = collect($product->image_gallery)
        ->reject(function ($path) use ($removedExistingImages) {
            return in_array($path, $removedExistingImages, true);
        })
        ->values();

    $initialGalleryImages = $visibleImagePaths
        ->map(function ($path) {
            return [
                'name' => basename($path),
                'path' => $path,
                'url' => \App\Support\PublicStorage::url($path),
                'existing' => true,
            ];
        })
        ->all();
@endphp

@section('scripts')
<script>
    (function () {
        const initialImages = @json($initialGalleryImages);

        const maxImages = 6;
        const fileInput = document.getElementById('productImages');
        const track = document.getElementById('galleryTrack');
        const thumbs = document.getElementById('galleryThumbs');
        const emptyState = document.getElementById('galleryEmpty');
        const counter = document.getElementById('galleryCounter');
        const prevButton = document.getElementById('galleryPrev');
        const nextButton = document.getElementById('galleryNext');
        const removedImagesContainer = document.getElementById('removedImagesContainer');
        const form = document.getElementById('editProductForm');
        const submitButton = form.querySelector('.edit-product-btn.primary');
        const progressBox = document.getElementById('editProductProgress');
        const progressFill = document.getElementById('editProductProgressFill');
        const progressValue = document.getElementById('editProductProgressValue');
        const progressLabel = document.getElementById('editProductProgressLabel');
        const successModal = document.getElementById('editProductSuccessModal');
        const successModalMessage = document.getElementById('editProductModalMessage');

        let galleryItems = initialImages.slice();
        let currentIndex = 0;

        function setProgress(percent, label = 'Uploading changes...') {
            progressBox.classList.add('is-visible');
            progressLabel.textContent = label;
            progressFill.style.width = `${percent}%`;
            progressValue.textContent = `${percent}%`;
        }

        function resetProgress() {
            progressBox.classList.remove('is-visible');
            progressFill.style.width = '0%';
            progressValue.textContent = '0%';
            progressLabel.textContent = 'Uploading changes...';
        }

        function showSuccessModal(message, redirectUrl) {
            successModalMessage.textContent = message;
            successModal.classList.add('is-visible');
            successModal.setAttribute('aria-hidden', 'false');

            window.setTimeout(() => {
                successModal.classList.remove('is-visible');
                successModal.setAttribute('aria-hidden', 'true');

                if (redirectUrl) {
                    window.location.href = redirectUrl;
                }
            }, 1600);
        }

        function syncRemovedInputs() {
            removedImagesContainer.innerHTML = '';

            initialImages.forEach(image => {
                const stillPresent = galleryItems.some(item => item.existing && item.path === image.path);

                if (!stillPresent) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'removed_existing_images[]';
                    input.value = image.path;
                    removedImagesContainer.appendChild(input);
                }
            });
        }

        function syncFileInput() {
            const transfer = new DataTransfer();
            galleryItems
                .filter(item => !item.existing && item.file)
                .forEach(item => transfer.items.add(item.file));
            fileInput.files = transfer.files;
        }

        function clampIndex() {
            if (galleryItems.length === 0) {
                currentIndex = 0;
                return;
            }

            currentIndex = Math.min(currentIndex, galleryItems.length - 1);
        }

        function renderGallery() {
            clampIndex();
            track.innerHTML = '';
            thumbs.innerHTML = '';

            emptyState.style.display = galleryItems.length ? 'none' : 'grid';
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
            counter.textContent = `${galleryItems.length} / ${maxImages} photos`;
            prevButton.disabled = galleryItems.length < 2 || currentIndex === 0;
            nextButton.disabled = galleryItems.length < 2 || currentIndex === galleryItems.length - 1;

            galleryItems.forEach((item, index) => {
                const slide = document.createElement('div');
                slide.className = 'gallery-slide';

                const slideImage = document.createElement('img');
                slideImage.src = item.url;
                slideImage.alt = item.name || `Product photo ${index + 1}`;

                const tag = document.createElement('div');
                tag.className = 'gallery-slide-tag';
                tag.textContent = item.existing ? 'Current photo' : 'New photo';

                slide.appendChild(slideImage);
                slide.appendChild(tag);
                track.appendChild(slide);

                const thumb = document.createElement('div');
                thumb.className = `gallery-thumb${index === currentIndex ? ' is-active' : ''}`;
                thumb.addEventListener('click', () => {
                    currentIndex = index;
                    renderGallery();
                });

                const thumbImage = document.createElement('img');
                thumbImage.src = item.url;
                thumbImage.alt = item.name || `Photo ${index + 1}`;

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'gallery-thumb-remove';
                removeButton.setAttribute('aria-label', `Remove ${item.name || `photo ${index + 1}`}`);
                removeButton.innerHTML = '&times;';
                removeButton.addEventListener('click', (event) => {
                    event.stopPropagation();

                    if (!item.existing && item.url.startsWith('blob:')) {
                        URL.revokeObjectURL(item.url);
                    }

                    galleryItems.splice(index, 1);
                    syncRemovedInputs();
                    syncFileInput();
                    renderGallery();
                });

                thumb.appendChild(thumbImage);
                thumb.appendChild(removeButton);
                thumbs.appendChild(thumb);
            });
        }

        prevButton.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex -= 1;
                renderGallery();
            }
        });

        nextButton.addEventListener('click', () => {
            if (currentIndex < galleryItems.length - 1) {
                currentIndex += 1;
                renderGallery();
            }
        });

        fileInput.addEventListener('change', () => {
            const availableSlots = Math.max(maxImages - galleryItems.length, 0);
            const incomingFiles = Array.from(fileInput.files || []).slice(0, availableSlots);

            incomingFiles.forEach(file => {
                galleryItems.push({
                    name: file.name,
                    url: URL.createObjectURL(file),
                    file,
                    existing: false,
                });
            });

            syncFileInput();
            renderGallery();
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(form);

            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';
            setProgress(0);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.addEventListener('progress', function (uploadEvent) {
                if (!uploadEvent.lengthComputable) {
                    return;
                }

                const percent = Math.min(100, Math.round((uploadEvent.loaded / uploadEvent.total) * 100));
                setProgress(percent);
            });

            xhr.addEventListener('load', function () {
                let data = {};

                try {
                    data = JSON.parse(xhr.responseText || '{}');
                } catch (error) {
                    data = {};
                }

                if (xhr.status < 200 || xhr.status >= 300) {
                    const messages = data.errors
                        ? Object.values(data.errors).flat()
                        : [data.message || 'Unable to update product right now.'];
                    alert(messages.join('\n'));
                    resetProgress();
                    return;
                }

                setProgress(100, 'Upload complete');
                showSuccessModal(data.message || 'Product updated successfully.', data.redirect);
            });

            xhr.addEventListener('error', function () {
                alert('Something went wrong while updating the product.');
                resetProgress();
            });

            xhr.addEventListener('loadend', function () {
                submitButton.disabled = false;
                submitButton.textContent = 'Save Changes';
            });

            xhr.send(formData);
        });

        syncRemovedInputs();
        renderGallery();
    })();
</script>
@endsection
