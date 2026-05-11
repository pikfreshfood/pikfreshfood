@extends('layouts.app')

@section('title', 'Add Product - PikFreshFood')
@section('hide_theme_toggle', 'hidden')
@section('hide_header', 'hidden')

@section('styles')
<style>
    .add-product-shell {
        min-height: calc(100vh - 86px);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 28px 16px;
    }
    .add-product-container {
        background: var(--bottom-sheet-bg);
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 2px 10px var(--shadow-color);
        width: 100%;
        max-width: 640px;
        border: 1px solid var(--border-color);
    }
    .add-product-container h1 {
        text-align: center;
        color: var(--primary-color);
        margin-bottom: 10px;
    }
    .add-product-copy {
        text-align: center;
        color: var(--muted-color);
        margin-bottom: 22px;
        line-height: 1.5;
    }
    .add-product-form {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .add-product-form input,
    .add-product-form select,
    .add-product-form textarea {
        width: 100%;
        padding: 12px 14px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--surface-bg);
        color: var(--text-color);
    }
    .add-product-form textarea {
        min-height: 110px;
        resize: vertical;
    }
    .upload-zone {
        border: 1px dashed var(--border-color);
        border-radius: 14px;
        padding: 18px;
        background: color-mix(in srgb, var(--primary-color) 5%, white 95%);
    }
    .upload-zone label {
        display: block;
        color: var(--text-color);
        font-weight: 700;
        margin-bottom: 10px;
    }
    .upload-help {
        color: var(--muted-color);
        font-size: 0.88rem;
        margin-top: 4px;
    }
    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
        gap: 12px;
        margin-top: 14px;
    }
    .image-preview-card {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background: var(--surface-alt);
        border: 1px solid var(--border-color);
        aspect-ratio: 1 / 1;
    }
    .image-preview-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .remove-preview-btn {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        border: none;
        background: rgba(0, 0, 0, 0.72);
        color: white;
        font-size: 1rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .error-list {
        display: none;
        padding: 12px 14px;
        border-radius: 10px;
        background: rgba(180, 38, 38, 0.12);
        color: #b42626;
        font-size: 0.9rem;
    }
    .error-list.is-visible { display: block; }
    .success-box {
        display: none;
        padding: 12px 14px;
        border-radius: 10px;
        background: rgba(47, 131, 105, 0.14);
        color: #1f7a62;
        font-size: 0.92rem;
    }
    .success-box.is-visible { display: block; }
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
    .add-product-submit {
        background: var(--primary-color);
        color: white;
        padding: 14px 16px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 700;
    }
    .add-product-submit:disabled {
        opacity: 0.7;
        cursor: wait;
    }
</style>
@endsection

@section('content')
<div class="add-product-shell">
    <div class="add-product-container">
        <h1>Add New Product</h1>
        <p class="add-product-copy">Create a product listing with pricing, stock, and up to 6 images. Images upload with AJAX and preview before submit.</p>

        <div class="error-list" id="addProductErrors"></div>
        <div class="success-box" id="addProductSuccess"></div>
        <div class="upload-progress" id="addProductProgress">
            <div class="upload-progress-meta">
                <span id="addProductProgressLabel">Uploading product...</span>
                <strong id="addProductProgressValue">0%</strong>
            </div>
            <div class="upload-progress-bar">
                <div class="upload-progress-fill" id="addProductProgressFill"></div>
            </div>
        </div>

        <form action="{{ route('vendor.store-product') }}" method="POST" enctype="multipart/form-data" class="add-product-form" id="addProductForm">
            @csrf
            <input type="text" name="name" placeholder="Product Name" required>
            <textarea name="description" placeholder="Description" rows="3"></textarea>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="fruits">Fruits</option>
                <option value="vegetables">Vegetables</option>
                <option value="grains">Grains</option>
                <option value="spices">Spices</option>
                <option value="nuts">Nuts</option>
            </select>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="number" name="stock_quantity" placeholder="Stock Quantity" required>
            <input type="text" name="unit" placeholder="Unit (e.g., kg, piece)" required>

            <div class="upload-zone">
                <label for="productImages">Product Images</label>
                <input type="file" id="productImages" accept="image/*" multiple>
                <div class="upload-help">Select up to 6 images. Click the remove icon to discard a preview before upload.</div>
                <div class="image-preview-grid" id="imagePreviewGrid"></div>
            </div>

            <button type="submit" class="add-product-submit" id="addProductSubmit">Add Product</button>
        </form>
    </div>
</div>

<div class="success-modal" id="addProductSuccessModal" aria-hidden="true">
    <div class="success-modal-card">
        <div class="success-modal-badge">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="m5 12.5 4.2 4.2L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h2>Upload Complete</h2>
        <p id="addProductModalMessage">Your product was added successfully.</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const form = document.getElementById('addProductForm');
        const fileInput = document.getElementById('productImages');
        const previewGrid = document.getElementById('imagePreviewGrid');
        const errorBox = document.getElementById('addProductErrors');
        const successBox = document.getElementById('addProductSuccess');
        const submitButton = document.getElementById('addProductSubmit');
        const progressBox = document.getElementById('addProductProgress');
        const progressFill = document.getElementById('addProductProgressFill');
        const progressValue = document.getElementById('addProductProgressValue');
        const progressLabel = document.getElementById('addProductProgressLabel');
        const successModal = document.getElementById('addProductSuccessModal');
        const successModalMessage = document.getElementById('addProductModalMessage');
        let selectedFiles = [];

        function renderErrors(messages) {
            if (!messages || messages.length === 0) {
                errorBox.classList.remove('is-visible');
                errorBox.innerHTML = '';
                return;
            }

            errorBox.classList.add('is-visible');
            errorBox.innerHTML = messages.map(message => `<div>${message}</div>`).join('');
        }

        function renderSuccess(message) {
            if (!message) {
                successBox.classList.remove('is-visible');
                successBox.textContent = '';
                return;
            }

            successBox.classList.add('is-visible');
            successBox.textContent = message;
        }

        function setProgress(percent, label = 'Uploading product...') {
            progressBox.classList.add('is-visible');
            progressLabel.textContent = label;
            progressFill.style.width = `${percent}%`;
            progressValue.textContent = `${percent}%`;
        }

        function resetProgress() {
            progressBox.classList.remove('is-visible');
            progressFill.style.width = '0%';
            progressValue.textContent = '0%';
            progressLabel.textContent = 'Uploading product...';
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

        function renderPreviews() {
            previewGrid.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const card = document.createElement('div');
                card.className = 'image-preview-card';

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.alt = file.name;
                img.onload = () => URL.revokeObjectURL(img.src);

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'remove-preview-btn';
                removeButton.setAttribute('aria-label', `Remove ${file.name}`);
                removeButton.textContent = '×';
                removeButton.addEventListener('click', () => {
                    selectedFiles.splice(index, 1);
                    renderPreviews();
                });

                card.appendChild(img);
                card.appendChild(removeButton);
                previewGrid.appendChild(card);
            });
        }

        fileInput.addEventListener('change', function () {
            const incomingFiles = Array.from(fileInput.files || []);

            for (const file of incomingFiles) {
                if (selectedFiles.length >= 6) {
                    break;
                }

                selectedFiles.push(file);
            }

            fileInput.value = '';
            renderErrors([]);
            renderPreviews();
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            renderErrors([]);
            renderSuccess('');
            resetProgress();

            if (selectedFiles.length === 0) {
                renderErrors(['Please select at least one product image.']);
                return;
            }

            const formData = new FormData(form);
            selectedFiles.forEach(file => formData.append('images[]', file));

            submitButton.disabled = true;
            submitButton.textContent = 'Uploading...';
            setProgress(0);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.addEventListener('progress', function (event) {
                if (!event.lengthComputable) {
                    return;
                }

                const percent = Math.min(100, Math.round((event.loaded / event.total) * 100));
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
                        : [data.message || 'Unable to upload product right now.'];
                    renderErrors(messages);
                    resetProgress();
                    return;
                }

                setProgress(100, 'Upload complete');
                renderSuccess(data.message || 'Product added successfully.');
                form.reset();
                selectedFiles = [];
                renderPreviews();

                showSuccessModal(data.message || 'Product added successfully.', data.redirect);
            });

            xhr.addEventListener('error', function () {
                renderErrors(['Something went wrong while uploading the product.']);
                resetProgress();
            });

            xhr.addEventListener('loadend', function () {
                submitButton.disabled = false;
                submitButton.textContent = 'Add Product';
            });

            xhr.send(formData);
        });
    })();
</script>
@endsection
