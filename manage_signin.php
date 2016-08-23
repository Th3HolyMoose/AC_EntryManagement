<form method="POST" onsubmit="return save();" action="manage.php" id="manage_pw_form">
    Password: <input type="text" name="p">
    <input type="submit">
</form>

<script type="text/javascript">
    var stored = localStorage.getItem("management_password");
    if(stored) {
        document.getElementById("manage_pw_form").p.value = stored;
        <?php
        if($incorrectManagePass == false) {
            echo 'document.getElementById("manage_pw_form").submit();';
        }
        ?>
    }
    function save() {
        localStorage.setItem("management_password", document.getElementById("manage_pw_form").p.value);
        return true;
    }
</script>


</body>
</html>