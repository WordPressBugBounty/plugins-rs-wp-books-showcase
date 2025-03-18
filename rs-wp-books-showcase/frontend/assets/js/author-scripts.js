jQuery(document).ready(function($) {
    var page = 1;
    var $container = $(".rswpbs-authors-masonry");
    var perRow = $container.data("per-row");
    var perPage = $container.data("per-page");
    var loading = false;
    var currentSearch = "";

    function loadAuthors(append = true, search = "") {
        if (loading) return;
        loading = true;

        $.ajax({
            url: rswpbsAjax.ajax_url,
            type: "POST",
            data: {
                action: "rswpbs_load_authors",
                page: page,
                per_row: perRow,
                per_page: perPage,
                search: search
            },
            success: function(response) {
                console.log("AJAX Response:", response);
                if (response.html) {
                    if (!append) $container.empty();
                    $container.append(response.html);
                    $("#rswpbs-load-more").toggle(response.has_more);
                    console.log("Has More:", response.has_more); // Debug log
                } else {
                    console.log("No HTML returned in response");
                }
                loading = false;
            },
            error: function(xhr, status, error) {
                console.log("AJAX Error:", status, error);
                loading = false;
            }
        });
    }

    loadAuthors();

    $("#rswpbs-load-more").on("click", function(e) {
        e.preventDefault();
        page++;
        loadAuthors(true, currentSearch);
    });

    $("#rswpbs-author-search").on("submit", function(e) {
        e.preventDefault();
        page = 1;
        currentSearch = $(this).find("input[name=author_search]").val();
        loadAuthors(false, currentSearch);
    });

    $(window).on("scroll", function() {
        // Prevent accidental triggers
    });
});