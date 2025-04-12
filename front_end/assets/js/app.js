document.addEventListener("DOMContentLoaded", async () => {
  const categories = await getCategories();
  const categoryList = document.getElementById("categoryList");
  const courseList = document.getElementById("courseList");

  function renderCategories(categories, parent = null, level = 0) {
    categories
      .filter(cat => cat.parent_id === parent)
      .forEach(cat => {
        const div = document.createElement("div");
        div.style.marginLeft = `${level * 15}px`;

        const courseCount = cat.count_of_courses > 0 ? ` (${cat.count_of_courses})` : "";

        div.textContent = `${cat.name}${courseCount}`;
        div.classList.add("category-menu");

        div.onclick = () => {
          document.querySelectorAll('.category-menu').forEach(el => el.classList.remove('active'));
          document.getElementById('heading-title').textContent = cat.name;
          div.classList.add('active');
          loadCourses(cat.id);
        };
        categoryList.appendChild(div);

        renderCategories(categories, cat.id, level + 1);
      });
  }

  async function loadCourses(categoryId = '') {
    const courses = await getCourses(categoryId);
    courseList.innerHTML = "";
    courses.forEach(course => {
      const div = document.createElement("div");
      div.classList.add("course");
      div.innerHTML = `
        <div class="preview" style="background-image: url('${course.preview}');"></div>
        <div class="detail">
          <div class="title"><strong>${course.name}</strong></div>
          <div class="desc">${course.description}</div>
        </div>
        <small class="category">${course.main_category_name}</small>
      `;
      courseList.appendChild(div);
    });
  }

  renderCategories(categories);
  await loadCourses();
});
