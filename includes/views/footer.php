</main>

<!-- ===== NEWSLETTER CTA ===== -->
<section class="newsletter-section">
    <div class="container">
        <h3><i class="fas fa-envelope" style="margin-right:8px;"></i>Stay in the Loop</h3>
        <p>Get notified about the latest car deals and new listings in your area.</p>
        <form class="newsletter-form"
            onsubmit="event.preventDefault();showToast('Thanks for subscribing! 🎉','success');this.reset();">
            <input type="email" placeholder="Enter your email" required>
            <button type="submit"><i class="fas fa-paper-plane" style="margin-right:6px;"></i>Subscribe</button>
        </form>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <h3><i class="fas fa-car-side" style="color:var(--primary);margin-right:8px;"></i>AutoMarket</h3>
                <p>Your trusted marketplace for quality pre-owned vehicles. Find the perfect car at the best price.</p>
                <div class="footer-social">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-column">
                <h4>Quick Links</h4>
                <a href="<?= baseUrl() ?>">Home</a>
                <a href="<?= baseUrl('search') ?>">Browse Cars</a>
                <a href="<?= baseUrl('cars/new') ?>">Sell a Car</a>
            </div>
            <div class="footer-column">
                <h4>Popular Brands</h4>
                <a href="<?= baseUrl('brand/Toyota') ?>">Toyota</a>
                <a href="<?= baseUrl('brand/BMW') ?>">BMW</a>
                <a href="<?= baseUrl('brand/Mercedes-Benz') ?>">Mercedes-Benz</a>
                <a href="<?= baseUrl('brand/Audi') ?>">Audi</a>
            </div>
            <div class="footer-column">
                <h4>Support</h4>
                <a href="#">Contact Us</a>
                <a href="#">FAQs</a>
                <a href="#">Terms of Service</a>
                <a href="#">Privacy Policy</a>
            </div>
        </div>
        <div class="footer-bottom">
            &copy;
            <?= date('Y') ?> AutoMarket. All rights reserved.
        </div>
    </div>
</footer>

<!-- Swiper.js -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- App JS -->
<script src="<?= asset('js/app.js') ?>"></script>

</body>

</html>