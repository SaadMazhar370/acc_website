<?php
$user_id = get_current_user_id();
$register_for = get_user_meta($user_id, 'register_for', true);
$parent_term = get_term_by('slug', $register_for, 'course-category');
?>


<style>
    .course-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .course-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .course-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .course-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }
    }
    
    @media (max-width: 480px) {
        .course-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .dropdowns-row {
    display: flex;
    gap: 16px;
    align-items: flex-end;
    margin-bottom: 30px;
    background: white;
    padding: 16px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.dropdown-group {
    flex: 1;
    min-width: 0;
}

.dropdown-group label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #555;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.select-wrapper {
    position: relative;
}

.select-wrapper select {
    width: 100%;
    padding: 10px 32px 10px 12px;
    font-size: 14px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    background-color: white;
    appearance: none;
    transition: all 0.2s ease;
    height: 40px;
}

.select-wrapper select:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
}

.select-wrapper select:disabled {
    background-color: #f9f9f9;
    color: #888;
}

.select-arrow {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    pointer-events: none;
    color: #999;
    width: 16px;
    height: 16px;
}

@media (max-width: 768px) {
    .dropdowns-row {
        flex-direction: column;
        gap: 12px;
    }
    
    .dropdown-group {
        width: 100%;
    }
}
    
</style>



<div class="dropdowns-row">
    <!-- Program Category -->
    <div class="dropdown-group">
        <label for="program-category">Program</label>
        <div class="select-wrapper">
            <select id="program-category" disabled>
                <option value="<?php echo esc_attr($parent_term->term_id); ?>">
                    <?php echo esc_html($parent_term->name); ?>
                </option>
            </select>
            <div class="select-arrow">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Specialization -->
    <div class="dropdown-group">
        <label for="child-category">Specialization</label>
        <div class="select-wrapper">
            <select id="child-category">
                <option value="">Select Specialization</option>
            </select>
            <div class="select-arrow">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </div>
    
 

    <!-- Course Type -->
    <div class="dropdown-group">
        <label for="course-type">Type</label>
        <div class="select-wrapper">
            <select id="course-type">
                <option value="">All Types</option>
                <option value="full-time">Full-Time</option>
                <option value="part-time">Part-Time</option>
            </select>
            <div class="select-arrow">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </div>
</div>


<div id="select-course-type-warning" class="mt-2 text-sm text-yellow-700 bg-yellow-100 border border-yellow-300 rounded p-3 hidden">
    ⚠️ Please select a Course Type (Full-Time or Part-Time) to see available courses.
</div>


<div id="course-type-msg" class="mt-4 text-sm text-gray-700 bg-yellow-100 border border-yellow-300 rounded p-3 hidden"></div>



<div id="courses-section">
    <h3 class="text-lg font-semibold mb-2">Courses</h3>

    Part-Time: You can enroll in up to 5 courses from any section. Minimum 1 course required.<br>
      Full-Time: You must enroll in 5 Compulsory Courses and 1 Elective Course.
    <div id="course-output"></div>
</div>


<script>
jQuery(document).ready(function ($) {
    const ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
    const parentId = '<?php echo $parent_term->term_id; ?>';
    const $childDropdown = $('#child-category');
    const $outputContainer = $('#course-output');
    const $courseType = $('#course-type');
    const $courseTypeMsg = $('#course-type-msg');
    const $typeWarning = $('#select-course-type-warning');

    // Load child categories (Specializations)
    $.post(ajaxurl, {
        action: 'get_grandchild_terms',
        term_id: parentId,
        nonce: '<?php echo wp_create_nonce("category_dropdown_nonce"); ?>'
    }, function (res) {
        if (res.success && res.data.length > 0) {
            let options = '<option value="">Select Specialization</option>';
            res.data.forEach(term => {
                options += `<option value="${term.term_id}">${term.name}</option>`;
            });
            $childDropdown.html(options);
        } else {
            $childDropdown.html('<option value="">No Specialization Found</option>');
        }
    });

    // Show full-time / part-time message
    $courseType.on('change', function () {
        const type = $(this).val();
        $typeWarning.hide();
        $courseTypeMsg.hide().removeClass('text-red-600 text-green-600');

        if (type === 'full-time') {
            $courseTypeMsg.html('✅ <strong>Full-Time:</strong> You must enroll in 5 Compulsory Courses and 1 Elective Course.').show();
        } else if (type === 'part-time') {
            $courseTypeMsg.html('✅ <strong>Part-Time:</strong> You can enroll in up to 5 courses from any section. Minimum 1 course required.').show();
        }

        const selectedChild = $childDropdown.val();
        if (selectedChild) {
            loadCourses(selectedChild, type);
        }
    });

    // On specialization change
    $childDropdown.on('change', function () {
        const selectedChild = $(this).val();
        const courseType = $courseType.val();

        // Check for grandchild categories
        $.post(ajaxurl, {
            action: 'check_has_grandchild',
            child_term_id: selectedChild,
            nonce: '<?php echo wp_create_nonce("load_courses_nonce"); ?>'
        }, function (res) {
            if (res.success && res.data.has_grandchild) {
                $('#course-type').closest('.dropdown-group').show();
                $typeWarning.show();
                $outputContainer.html(''); // Hide courses until type is selected
            } else {
                $('#course-type').val('');
                $('#course-type').closest('.dropdown-group').hide();
                $typeWarning.hide();
                $courseTypeMsg.hide();

                loadCourses(selectedChild, '');
            }
        });
    });

    // Load courses (helper)
    function loadCourses(childId, type) {
        $outputContainer.html('<div class="loading-courses">Loading courses...</div>');

        $.post(ajaxurl, {
            action: 'load_courses_grouped_by_grandchild',
            child_term_id: childId,
            course_type: type,
            nonce: '<?php echo wp_create_nonce("load_courses_nonce"); ?>'
        }, function (res) {
            if (res.success) {
                $outputContainer.html(res.data.html);
            } else {
                $outputContainer.html('<p>No courses found.</p>');
            }
        });
    }

    // Course select button logic
    $('body').on('click', '.select-course-btn', function () {
        const $btn = $(this);
        const courseId = $btn.data('course-id');
        const courseType = $btn.data('course-type');
        const productId = $btn.data('product-id');

        let selectedCourses = JSON.parse(localStorage.getItem('selectedCourses') || '[]');

        const type = $('#course-type').val();

        if (type === 'full-time') {
            const compulsoryCount = selectedCourses.filter(c => c.type === 'compulsory').length;
            const electiveCount = selectedCourses.filter(c => c.type === 'elective').length;

            if (courseType === 'compulsory' && compulsoryCount >= 5) {
                alert('You can only select 5 compulsory courses.');
                return;
            }
            if (courseType === 'elective' && electiveCount >= 1) {
                alert('You can only select 1 elective course.');
                return;
            }
        }

        if (type === 'part-time' && selectedCourses.length >= 5) {
            alert('You can only select up to 5 courses.');
            return;
        }

        if (productId) {
            $.post(ajaxurl, {
                action: 'custom_add_to_cart',
                product_id: productId,
            }, function (res) {
                if (res.success) {
                    selectedCourses.push({ id: courseId, type: courseType });
                    localStorage.setItem('selectedCourses', JSON.stringify(selectedCourses));
                    $btn.addClass('bg-green-600').text('Selected').prop('disabled', true);
                } else {
                    alert('Failed to add to cart.');
                }
            });
        } else {
            alert('Invalid product.');
        }
    });
});
</script>

