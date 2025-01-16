$(document).ready(() => {
    const $categorySelect = $('#categorySelect'); 

    const loadCategory = async () => {
        try {
            const response = await axios.get('http://localhost:3000/e-commerce-inventory-management/backend/category/sub_category_get.php');
            console.log(response.data);

            let subCategories = response.data.subcategories;

            
            if (response.data.status === 'success' && subCategories) {
                $categorySelect.empty(); 
                $categorySelect.append('<option value="">Select Category</option>');

                $.each(subCategories, (index, category) => {
                    $categorySelect.append(new Option(category.name, category.id));
                });
            } else {
                console.error('No categories found or status not success:', response.data.message);
            }

        } catch (error) {
            console.error('Error loading categories:', error);
        }
    };

    $('#deleteCategoryModal').on('show.bs.modal', loadCategory);

    $('#confirmDeleteButton').on('click', async () => {
        const categoryId = $categorySelect.val(); 

        try {
            
            const response = await axios({
                method: 'post',
                url: 'http://localhost:3000/e-commerce-inventory-management/backend/category/delete.php',
                // headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
               
                data: new URLSearchParams({
                    category_id: categoryId,

                })
            });

            if (response.data.status === 'success') {
              
                console.log('category deleted succefully')
                location.reload()
                $('#deleteCategoryModal').modal('hide');
                loadCategory();
            }
        } catch (error) {
            console.error('Error deleting category:', error);
        }
    });
});
