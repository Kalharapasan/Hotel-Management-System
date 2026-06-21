<?php
/**
 * Reusable "Browse Assets" picker.
 * Scans the assets already shipped inside this project (assets/images
 * and the uploads/ folders) and lets the admin pick one instead of
 * typing a URL or uploading a brand new file. Include this partial
 * once near the end of any admin page, then call:
 *
 *   openAssetPicker('targetInputId', 'previewImgId')
 *
 * from a button next to an image field. 'previewImgId' is optional.
 */
$__assetPickerRoot   = dirname(__DIR__, 3); // project root
$__assetPickerImages = [];
$__assetScanDirs = [
    'assets/images',
    'assets/img',
    'uploads/hotels',
    'uploads/rooms',
    'uploads/customers',
    'uploads/employees',
];

foreach ($__assetScanDirs as $__dir) {
    $__full = $__assetPickerRoot . '/' . $__dir;
    if (!is_dir($__full)) continue;
    $__it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($__full, FilesystemIterator::SKIP_DOTS));
    foreach ($__it as $__file) {
        if ($__file->isFile() && preg_match('/\.(jpe?g|png|gif|webp|svg)$/i', $__file->getFilename())) {
            $__rel = ltrim(str_replace($__assetPickerRoot, '', $__file->getPathname()), '/\\');
            $__rel = str_replace('\\', '/', $__rel);
            // de-dupe (assets/images and assets/img overlap on a few files)
            $__assetPickerImages[$__rel] = $__rel;
        }
    }
}
$__assetPickerImages = array_values($__assetPickerImages);
sort($__assetPickerImages);
?>
<div id="assetPickerModal" class="fixed inset-0 z-50 hidden bg-black/50 p-4 items-center justify-center">
    <div class="bg-white rounded-3xl w-full max-w-3xl max-h-[80vh] flex flex-col overflow-hidden shadow-xl">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Choose an Image from Assets</h3>
            <button type="button" onclick="closeAssetPicker()" class="text-slate-400 hover:text-slate-700 text-2xl leading-none">&times;</button>
        </div>
        <div class="p-4 border-b border-slate-100">
            <input type="text" id="assetPickerSearch" oninput="filterAssetPicker()" placeholder="Search images..." class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div id="assetPickerGrid" class="p-6 grid grid-cols-3 sm:grid-cols-4 gap-4 overflow-y-auto">
            <?php foreach ($__assetPickerImages as $__img): ?>
            <button type="button"
                class="asset-picker-item border border-slate-200 rounded-xl overflow-hidden hover:ring-2 hover:ring-indigo-500 transition bg-white"
                data-path="<?php echo htmlspecialchars($__img); ?>"
                onclick="selectAssetPickerImage('<?php echo htmlspecialchars($__img, ENT_QUOTES); ?>')">
                <img src="<?php echo asset_url($__img); ?>" class="w-full h-20 object-cover bg-slate-50" loading="lazy" alt="">
                <span class="block text-[10px] text-slate-500 px-1 py-1 truncate"><?php echo htmlspecialchars(basename($__img)); ?></span>
            </button>
            <?php endforeach; ?>
            <?php if (empty($__assetPickerImages)): ?>
                <p class="col-span-full text-center text-slate-400 py-8">No assets found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const ASSET_PICKER_BASE_URL = "<?php echo BASE_URL; ?>";
let __assetPickerTargetInput = null;
let __assetPickerTargetPreview = null;

function openAssetPicker(inputId, previewId) {
    __assetPickerTargetInput = inputId;
    __assetPickerTargetPreview = previewId || null;
    const modal = document.getElementById('assetPickerModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeAssetPicker() {
    const modal = document.getElementById('assetPickerModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function selectAssetPickerImage(path) {
    if (__assetPickerTargetInput) {
        const input = document.getElementById(__assetPickerTargetInput);
        if (input) input.value = path;
    }
    if (__assetPickerTargetPreview) {
        const preview = document.getElementById(__assetPickerTargetPreview);
        if (preview) {
            preview.src = ASSET_PICKER_BASE_URL + '/' + path.replace(/^\/+/, '');
            preview.classList.remove('hidden');
        }
    }
    closeAssetPicker();
}

function filterAssetPicker() {
    const q = document.getElementById('assetPickerSearch').value.toLowerCase();
    document.querySelectorAll('.asset-picker-item').forEach(function (el) {
        const path = (el.getAttribute('data-path') || '').toLowerCase();
        el.style.display = path.includes(q) ? '' : 'none';
    });
}

// Instantly preview an image chosen from the admin's own computer
// (before the form is even submitted), via a <input type="file" onchange="previewLocalFile(this,'xxxPreview')">
function previewLocalFile(fileInput, previewId) {
    const file = fileInput.files && fileInput.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (e) {
        const preview = document.getElementById(previewId);
        if (preview) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }
    };
    reader.readAsDataURL(file);
}

// Close modal on backdrop click
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('assetPickerModal');
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeAssetPicker();
        });
    }
});
</script>
