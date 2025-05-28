<?php
// Ensure this file gets included in all pages
?>
<script>
// Initialize theme immediately before DOM loads
const savedTheme = localStorage.getItem("theme");
if (savedTheme === "light") {
    document.documentElement.classList.add("light-mode");
}
</script>
<button id="theme-toggle" class="theme-icon-btn" aria-label="Toggle Theme"><?php echo (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'light') ? '🌞' : '🌙'; ?></button>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const toggleBtn = document.getElementById("theme-toggle");
    const body = document.body;

    // Apply saved theme from localStorage
    if (localStorage.getItem("theme") === "light") {
        body.classList.add("light-mode");
        toggleBtn.innerText = "🌞";
    }

    // Toggle theme
    toggleBtn?.addEventListener("click", () => {
        body.classList.toggle("light-mode");
        const isLight = body.classList.contains("light-mode");
        toggleBtn.innerText = isLight ? "🌞" : "🌙";
        localStorage.setItem("theme", isLight ? "light" : "dark");
        
        // Also set a cookie for server-side awareness
        document.cookie = `theme=${isLight ? 'light' : 'dark'}; path=/; max-age=31536000`; // 1 year
    });
});
</script>
