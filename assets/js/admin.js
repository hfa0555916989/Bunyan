/* ============================================================
   بنيان رسلان - Admin Panel JavaScript
   ============================================================ */

const CSRF = document.querySelector('input[name="csrf_token"]')?.value || '';

// ─── Upload Tabs ───
document.querySelectorAll('.upload-tab').forEach(tab => {
  tab.addEventListener('click', () => {
    document.querySelectorAll('.upload-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.upload-panel').forEach(p => p.classList.remove('active'));
    tab.classList.add('active');
    document.getElementById('tab-' + tab.dataset.tab)?.classList.add('active');
  });
});

// ─── Drop Zones ───
function setupDropZone(zoneId, inputId, previewType) {
  const zone  = document.getElementById(zoneId);
  const input = document.getElementById(inputId);
  if (!zone || !input) return;

  zone.addEventListener('click', () => input.click());

  zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
  zone.addEventListener('dragleave', ()  => zone.classList.remove('dragover'));
  zone.addEventListener('drop', e => {
    e.preventDefault(); zone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file) setFile(input, file, previewType);
  });

  input.addEventListener('change', () => {
    if (input.files[0]) setFile(input, input.files[0], previewType);
  });
}

function setFile(input, file, type) {
  const dt = new DataTransfer();
  dt.items.add(file);
  input.files = dt.files;

  if (type === 'image') {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('previewImg').src = e.target.result;
      document.getElementById('imagePreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
  } else {
    const url = URL.createObjectURL(file);
    document.getElementById('previewVideo').src = url;
    document.getElementById('videoPreview').style.display = 'block';
  }
}

function clearPreview(type) {
  if (type === 'image') {
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('previewImg').src = '';
    document.getElementById('imageFile').value = '';
  } else {
    document.getElementById('videoPreview').style.display = 'none';
    const v = document.getElementById('previewVideo');
    v.pause(); v.src = '';
    document.getElementById('videoFile').value = '';
  }
}

setupDropZone('imageDrop', 'imageFile', 'image');
setupDropZone('videoDrop', 'videoFile', 'video');

// ─── Upload Form Handler ───
function setupUploadForm(formId, progressId, fillId, pctId, btnId, msgId) {
  const form = document.getElementById(formId);
  if (!form) return;

  form.addEventListener('submit', async e => {
    e.preventDefault();
    const fileInput = form.querySelector('input[type="file"]');
    if (!fileInput?.files[0]) {
      showMsg(msgId, 'error', 'الرجاء اختيار ملف أولاً');
      return;
    }

    const btn     = document.getElementById(btnId);
    const progWrap = document.getElementById(progressId);
    const fill    = document.getElementById(fillId);
    const pct     = document.getElementById(pctId);

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الرفع...';
    progWrap.style.display = 'flex';
    hideMsg(msgId);

    const fd = new FormData(form);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/admin332233/upload', true);

    xhr.upload.onprogress = ev => {
      if (ev.lengthComputable) {
        const p = Math.round((ev.loaded / ev.total) * 100);
        fill.style.width = p + '%';
        pct.textContent  = p + '%';
      }
    };

    xhr.onload = () => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-upload"></i> رفع الملف';
      progWrap.style.display = 'none';
      fill.style.width = '0%';
      pct.textContent = '0%';

      try {
        const res = JSON.parse(xhr.responseText);
        if (res.success) {
          showMsg(msgId, 'success', '<i class="fas fa-check-circle"></i> ' + res.message);
          form.reset();
          clearPreview(formId.includes('image') ? 'image' : 'video');
          setTimeout(() => location.reload(), 1400);
        } else {
          showMsg(msgId, 'error', '<i class="fas fa-exclamation-circle"></i> ' + res.message);
        }
      } catch {
        showMsg(msgId, 'error', '<i class="fas fa-exclamation-circle"></i> حدث خطأ، حاول مجدداً');
      }
    };

    xhr.onerror = () => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-upload"></i> رفع الملف';
      progWrap.style.display = 'none';
      showMsg(msgId, 'error', '<i class="fas fa-exclamation-circle"></i> فشل الاتصال بالخادم');
    };

    xhr.send(fd);
  });
}

setupUploadForm('imageUploadForm', 'imageProgress', 'imageFill', 'imagePct', 'imageBtn', 'imageMsg');
setupUploadForm('videoUploadForm', 'videoProgress', 'videoFill', 'videoPct', 'videoBtn', 'videoMsg');

function showMsg(id, type, html) {
  const el = document.getElementById(id);
  if (!el) return;
  el.className = 'upload-msg ' + type;
  el.innerHTML = html;
}
function hideMsg(id) {
  const el = document.getElementById(id);
  if (el) el.className = 'upload-msg';
}

// ─── Delete Media ───
let pendingDeleteId   = null;
let pendingDeleteType = null;

function deleteMedia(id, type) {
  pendingDeleteId   = id;
  pendingDeleteType = type;
  document.getElementById('deleteModal').classList.add('open');
}

function closeModal() {
  document.getElementById('deleteModal').classList.remove('open');
  pendingDeleteId   = null;
  pendingDeleteType = null;
}

document.getElementById('deleteModal')?.addEventListener('click', e => {
  if (e.target.id === 'deleteModal') closeModal();
});

document.getElementById('confirmDeleteBtn')?.addEventListener('click', async () => {
  if (!pendingDeleteId) return;

  const btn = document.getElementById('confirmDeleteBtn');
  btn.disabled  = true;
  btn.textContent = 'جاري الحذف...';

  try {
    const res = await fetch('/admin332233/delete', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: pendingDeleteId, type: pendingDeleteType, csrf_token: CSRF }),
    });
    const data = await res.json();
    if (data.success) {
      const prefix = pendingDeleteType === 'image' ? 'img-' : 'vid-';
      document.getElementById(prefix + pendingDeleteId)?.remove();
      closeModal();
    } else {
      alert('خطأ: ' + data.message);
    }
  } catch {
    alert('فشل الاتصال بالخادم');
  } finally {
    btn.disabled  = false;
    btn.textContent = 'حذف';
  }
});

// ─── Sidebar Toggle ───
document.querySelector('.sidebar-toggle')?.addEventListener('click', () => {
  document.getElementById('sidebar')?.classList.toggle('open');
});
