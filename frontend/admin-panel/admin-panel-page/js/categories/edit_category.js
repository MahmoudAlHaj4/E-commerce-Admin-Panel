$(document).ready(() => {
    const $categorySelect = $('#editCategorySelect');
    const $editCategoryName = $('#editCategoryName');
    const $editCategoryForm = $('#editCategoryForm');


    const loadCategory = async () => {
        try {
            const response = await axios.get('http://localhost:3000/e-commerce-inventory-management/backend/category/get_all.php');
            console.log(response.data);

            let categories = response.data.categories;

            $categorySelect.empty(); 
            $categorySelect.append('<option value="">Select a category</option>');

            $.each(categories, (index, category) => {
                $categorySelect.append(new Option(category.name, category.id)); 
            });
        } catch (error) {
            console.log('Error loading categories:', error);
        }
    };


    $('#editCategoryModal').on('show.bs.modal', loadCategory);

    $categorySelect.on('change', async function () {
        const categoryId = $(this).val();

        if (categoryId) {
            try {
                const response = await axios.get(`http://localhost:3000/e-commerce-inventory-management/backend/category/get.php?category_id=${categoryId}`);
                const category = response.data.category;
                console.log('the category',category);


                if (category && category.name) {
                    $editCategoryName.val(category.name);
                } else {
                    console.log('Category data is missing or undefined');
                }
            } catch (error) {
                console.error('Error fetching category details:', error);
            }
        } else {
            $editCategoryName.val(''); 
        }
    });

    $('#updatebtn').on('click', async (event)=>{
        event.preventDefault()

        const categoryId = $categorySelect.val();
        const categoryName = $editCategoryName.val();


        const formData = new FormData();
        formData.append('category_id', categoryId);
        formData.append('name', categoryName);

        try {
            const response = await axios.post('http://localhost:3000/e-commerce-inventory-management/backend/category/update.php', formData);
            console.log(response.data);

            if (response.data.status === 'success') {
                console.log('Category updated successfully');

                $('#editCategoryModal').modal('hide');
                location.reload();

            } else {
                console.log('Failed to update category:', response.data.message);
            }
        } catch (error) {
            console.error('Error updating category:', error);
        }
    });

    })
;
