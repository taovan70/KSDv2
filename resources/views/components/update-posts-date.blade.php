<div class="update-post-date-block_wrapper">
    <div class="update-post-date-block">
        <input type="number" class="form-control update-post-date-block_days_input" placeholder="дней">
        <button href="/admin/make-article" class="btn btn-primary update-post-date-block__button" data-style="zoom-in">
            <span class="ladda-label">Обновить даты постов</span></button>
    </div>
    <div class="update-post-date-block__error"></div>
</div>
<script src="<?php echo e(asset('/js/lib/create-adv-block.js')); ?>"></script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const days = $(".update-post-date-block_days_input");
    $(".update-post-date-block__button").on('click', function () {
      if (days.val() > 0) {
        $.ajax({
          url: "/admin/article/update-posts-date",
          cache: false,
          contentType: 'application/json',
          type: "POST",
          data: JSON.stringify({
            days: days.val(),
          }),
          success: function (data) {
            if (data.status === 'success') {
              swal({
                title: "Дата статей была обновлена",
                icon: "success",
              })
              $(".update-post-date-block__error").text('');
            } else {
              swal({
                title: "Не удалось обновить дату",
                icon: "error",
              })
              $(".update-post-date-block__error").text('');
            }
          },
          error: function (data) {
            swal({
              title: "Не удалось обновить дату",
              icon: "error",
            })
            $(".update-post-date-block__error").text('');
          }
        })
      } else {
        $(".update-post-date-block__error").text("Введите количество дней");
      }
    })
  });
</script>

<style>
    .update-post-date-block {
        display: flex;
        justify-content: center;
        align-items: baseline;
        gap: 15px;
    }

    .update-post-date-block_wrapper {
        margin-top: 20px;
        padding-left: 15px;
    }

    .update-post-date-block__button {
        min-width: 200px;
    }

    .update-post-date-block__error {
        font-size: 12px;
        color: red;
    }

</style>
