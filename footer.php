</main>
</div>
<footer id="footer" role="contentinfo">
   
    <div id="footer-content">
        <?php echo wp_kses_post(get_theme_mod('footer_content', '')); ?>
    </div>
     <div id="copyright">
        &copy;
        <?php echo esc_html(date_i18n(__('Y', 'tctalent'))); ?>
        <?php echo esc_html(get_bloginfo('name')); ?> | Located in Seattle, WA
    </div>
</footer>

</div>
<?php wp_footer(); ?>
</body>

</html>