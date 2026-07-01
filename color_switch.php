<div class="color-buttons">
    <label class="theme-switch" title="Tukar mod cerah atau gelap" aria-label="Tukar mod cerah atau gelap">
        <input type="checkbox" class="theme-switch__checkbox" id="btn-theme-toggle" role="switch" onchange="toggleTheme(this.checked ? 'dark' : 'light')">
        <span class="theme-switch__container" aria-hidden="true">
            <span class="theme-switch__clouds"></span>
            <span class="theme-switch__stars-container">
                <svg viewBox="0 0 64 34" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="8" cy="8" r="2.2"></circle>
                    <circle cx="28" cy="5" r="1.6"></circle>
                    <circle cx="48" cy="11" r="2"></circle>
                    <circle cx="18" cy="23" r="1.8"></circle>
                    <circle cx="40" cy="26" r="1.4"></circle>
                    <path d="M56 23l1.5 3 3 .4-2.2 2.1.6 3-2.9-1.5-2.7 1.5.5-3-2.2-2.1 3.1-.4z"></path>
                </svg>
            </span>
            <span class="theme-switch__circle-container">
                <span class="theme-switch__sun-moon-container">
                    <span class="theme-switch__moon">
                        <span class="theme-switch__spot"></span>
                        <span class="theme-switch__spot"></span>
                        <span class="theme-switch__spot"></span>
                    </span>
                </span>
            </span>
        </span>
    </label>
    <span class="divider"></span>
    <button type="button" class="btn-font" data-size="small" aria-label="Saiz tulisan kecil" onclick="setFontSize('small',  this)">S</button>
    <button type="button" class="btn-font" data-size="medium" aria-label="Saiz tulisan sederhana" onclick="setFontSize('medium', this)">M</button>
    <button type="button" class="btn-font" data-size="large" aria-label="Saiz tulisan besar" onclick="setFontSize('large',  this)">L</button>
</div>

<script>
    (function() {
        //Dapatkan tema yang pernah disimpan dalam pelayar.
        var savedTheme = localStorage.getItem('theme');
        var oldTheme = localStorage.getItem('tema');
        if (!savedTheme && oldTheme) {
            //Tukar nama simpanan lama kepada nama simpanan baharu.
            savedTheme = oldTheme === 'default' ? 'dark' : 'light';
            localStorage.setItem('theme', savedTheme);
            localStorage.removeItem('tema');
        }
        //Gunakan tema dan saiz tulisan yang dipilih pengguna.
        savedTheme = savedTheme || 'dark';
        applyTheme(savedTheme);

        var savedFont = localStorage.getItem('fontSize') || 'medium';
        applyFontSize(savedFont);
        updateFontButtons(savedFont);
    })();

    //Fungsi applyTheme() menukar paparan antara mod cerah dan gelap.
    function applyTheme(theme) {
        var body = document.body;
        var html = document.documentElement;
        if (theme === 'light') {
            body.classList.add('light-mode');
            html.classList.add('light-mode');
        } else {
            body.classList.remove('light-mode');
            html.classList.remove('light-mode');
        }
        //Kemas kini keadaan suis tema untuk aksesibiliti.
        var toggle = document.getElementById('btn-theme-toggle');
        var switchLabel = document.querySelector('.theme-switch');
        if (toggle) {
            toggle.checked = theme === 'dark';
            toggle.setAttribute('aria-checked', theme === 'dark' ? 'true' : 'false');
        }
        if (switchLabel) {
            switchLabel.title = theme === 'light' ? 'Tukar ke Mod Gelap' : 'Tukar ke Mod Cerah';
            switchLabel.setAttribute('aria-label', theme === 'light' ? 'Tukar ke Mod Gelap' : 'Tukar ke Mod Cerah');
        }
    }

    //Fungsi toggleTheme() menyimpan pilihan tema pengguna.
    function toggleTheme(theme) {
        var isLight = document.body.classList.contains('light-mode');
        var newTheme = theme || (isLight ? 'dark' : 'light');

        //Jalankan animasi ringkas semasa tema ditukar.
        document.body.classList.remove('theme-changing');
        void document.body.offsetWidth;
        applyTheme(newTheme);
        document.body.classList.add('theme-changing');
        clearTimeout(window._themeTimer);
        window._themeTimer = setTimeout(function() {
            document.body.classList.remove('theme-changing');
        }, 720);

        localStorage.setItem('theme', newTheme);
    }

    //Fungsi applyFontSize() menetapkan saiz tulisan pada elemen html.
    function applyFontSize(size) {
        var html = document.documentElement;
        html.classList.remove('font-small', 'font-medium', 'font-large');
        html.classList.add('font-' + size);
    }

    //Fungsi updateFontButtons() menandakan butang saiz tulisan yang aktif.
    function updateFontButtons(size) {
        document.querySelectorAll('.btn-font').forEach(function(btn) {
            var isActive = btn.dataset.size === size;
            btn.classList.toggle('active', isActive);
            btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
    }

    //Fungsi setFontSize() menyimpan pilihan saiz tulisan pengguna.
    function setFontSize(size) {
        applyFontSize(size);
        updateFontButtons(size);

        localStorage.setItem('fontSize', size);
    }
</script>
