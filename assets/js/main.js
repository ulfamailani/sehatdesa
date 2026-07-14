document.addEventListener('DOMContentLoaded', function () {
  // --- Menu mobile ---
  var toggle = document.getElementById('navToggle');
  var headerInner = document.getElementById('siteHeaderInner');
  if (toggle && headerInner) {
    toggle.addEventListener('click', function () {
      var isOpen = headerInner.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }

  // --- Chat AJAX ---
  var chatForm = document.getElementById('chatForm');
  if (!chatForm) return;

  var chatInput = document.getElementById('chatInput');
  var chatLog = document.getElementById('chatLog');
  var submitBtn = chatForm.querySelector('button[type="submit"]');

  function appendMessage(role, text) {
    var empty = chatLog.querySelector('.chat-empty');
    if (empty) empty.remove();

    var div = document.createElement('div');
    div.className = role === 'user' ? 'msg msg--user' : 'msg msg--ai';

    if (role === 'ai') {
      var label = document.createElement('span');
      label.className = 'msg__label';
      label.textContent = 'Asisten SehatDesa';
      div.appendChild(label);
      div.appendChild(document.createTextNode(text));
    } else {
      div.textContent = text;
    }

    chatLog.appendChild(div);
    chatLog.scrollTop = chatLog.scrollHeight;
  }

  chatForm.addEventListener('submit', function (evt) {
    evt.preventDefault();
    var pesan = chatInput.value.trim();
    if (!pesan) return;

    appendMessage('user', pesan);
    chatInput.value = '';
    submitBtn.disabled = true;
    submitBtn.textContent = 'Mengirim...';

    fetch('api/chat.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ pesan: pesan }),
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (data.error) {
          appendMessage('ai', 'Maaf, terjadi kendala: ' + data.error);
        } else {
          appendMessage('ai', data.jawaban);
        }
      })
      .catch(function () {
        appendMessage('ai', 'Maaf, terjadi kesalahan jaringan. Silakan coba lagi.');
      })
      .finally(function () {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Kirim';
      });
  });
});
