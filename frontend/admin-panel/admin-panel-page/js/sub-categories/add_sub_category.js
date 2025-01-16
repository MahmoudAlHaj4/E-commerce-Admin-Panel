$(document).ready(() => {
    const $categorySelect = $('#subCategorySelect'); 
    const $subcategoryName = $('#subCategoryName');
    const $addSubCategoryForm = $('.addSubCategoryForm'); 
    const $errorDisplay = $('#errorDisplay'); 

    
    const loadCategories = async () => {
        try {
            const response = await axios.get('http://localhost:3000/e-commerce-inventory-management/backend/category/get_all.php');
            console.log(response.data);

            let categories = response.data.categories;

            $categorySelect.empty(); 
            $categorySelect.append('<option value="">Select a Sub-category</option>');

            $.each(categories, (index, category) => {
                $categorySelect.append(new Option(category.name, category.id)); 
            });
        } catch (error) {
            console.log('Error loading categories:', error);
        }
    };

    $('#addsubCategoryModal').on('show.bs.modal', loadCategories);

    $addSubCategoryForm.on('submit', async (event) => {
        event.preventDefault(); 

        const categoryId = $categorySelect.val();
        const subcategoryName = $subcategoryName.val();

       

        const formData = new FormData();
        formData.append('category_id', categoryId);
        formData.append('name', subcategoryName);

        try {
            const response = await axios.post('http://localhost:3000/e-commerce-inventory-management/backend/category/sub_category_add.php', formData);
            console.log(response.data);

            if (response.data.status === 'success') {
                console.log('Subcategory added successfully');

                $('#addsubCategoryModal').modal('hide');
                location.reload(); 
            } else {
                console.log('Failed to add subcategory:', response.data.message);
                $errorDisplay.text(response.data.message); 
            }
        } catch (error) {
            console.error('Error adding subcategory:', error);
            $errorDisplay.text('An error occurred while adding the subcategory.');
        }
    });
});
