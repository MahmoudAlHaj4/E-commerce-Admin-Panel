$(document).ready(() => {
    const $categorySelect = $('#subCategorySelect2'); 
    const $subcategoryName = $('#subCategoryName2');
    const $addSubCategoryForm = $('.addSubCategoryForm2'); 
    const $errorDisplay = $('#errorDisplay2'); 

    
    const loadCategories = async () => {
        try {
            const response = await axios.get('http://localhost:3000/e-commerce-inventory-management/backend/category/sub_category_get.php');
            console.log(response.data);

            let subcategories = response.data.subcategories;

            $categorySelect.empty(); 
            $categorySelect.append('<option value="">Select a Sub-Category</option>');

            $.each(subcategories, (index, category) => {
                $categorySelect.append(new Option(category.name, category.id)); 
            });
        } catch (error) {
            console.log('Error loading categories:', error);
        }
    };
    loadCategories()
})