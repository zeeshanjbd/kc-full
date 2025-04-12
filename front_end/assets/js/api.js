const API_BASE = 'http://api.cc.localhost';

async function getCategories() {
  const res = await fetch(`${API_BASE}/categories`);
  return await res.json();
}

async function getCourses(categoryId = '') {
  const res = await fetch(`${API_BASE}/courses${categoryId ? `?category_id=${categoryId}` : ''}`);
  return await res.json();
}
