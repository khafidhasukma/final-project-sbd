</div> <!-- /container -->

<footer class="bg-light text-center py-3">
  <small>&copy; <?= date('Y') ?> Inventory App. All rights reserved.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
  integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
  integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</body>
<script>
// Auto-dismiss alert after 3 seconds
const alertBox = document.getElementById('alert-success');
if (alertBox) {
  setTimeout(() => {
    // Bootstrap 5 uses fade and show classes for animation
    alertBox.classList.remove('show');
    alertBox.classList.add('d-none');
  }, 3000);
}
</script>

</html>