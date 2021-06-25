<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<textarea class="listNames" name="" id="" cols="30" rows="10"
          value="<?= implode("<br>", $namesArr); ?>"><?= implode("\n", $namesArr); ?></textarea>

<button class="updateData">Обновить данные по введённым данным</button>
<br>
<br>
<br>
<div class="result">
    <?= $this->render('_block/repositories', [
        'repositories' => $repositories ?? [],
    ]); ?>
</div>


</body>

<script>
    $(document).on('click', '.updateData', function (e) {
        updateData()
    });

    setUpdateInfo();

    function setUpdateInfo() {
        setInterval(function () {
            updateData();
        }, 60 * 60 * 10);
    }

    function updateData() {

        var data = $('.listNames').val();

        $.ajax({
            url: '/site/get-repositories',
            method: "post",
            data: {data: data},

            success: function (data) {
                $('.result').html(data);
            }
        });
    }
</script>

