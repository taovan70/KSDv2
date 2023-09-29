$(document).ready(function () {
  // Select the select element by its name attribute
  var selectElement = $('select[name="adv_page_id"]');

  // Initial value
  var currentValue = selectElement.val();
  console.log("Initial value: " + currentValue);

  // Watch for changes
  selectElement.on("change", function () {
    // Get the current value when the select changes
    var newValue = selectElement.val();

    if (newValue === "3"){
        $('input[name="number_of_elements_from_beginning"]').parent().show();
        $('select[name="after_element"]').parent().show();
    } else {
        $('input[name="number_of_elements_from_beginning"]').parent().hide();
        $('select[name="after_element"]').parent().hide();
    }
  });
});
