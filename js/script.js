
$(document).ready(function(){
    $("#select_all").click(function(){
        $(".checkbox").prop("checked", this.checked);
    });

    // Optional: Deselect 'Select All' if any individual checkbox is unchecked
    $(".checkbox").click(function(){
        if(!$(this).is(":checked")) {
            $("#select_all").prop("checked", false);
        }
        // Check 'Select All' if all checkboxes are checked
        else if ($(".checkbox:checked").length === $(".checkbox").length) {
            $("#select_all").prop("checked", true);
        }
    });
});
