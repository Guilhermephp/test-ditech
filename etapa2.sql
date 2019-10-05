SELECT dept_name, first_name, last_name, TIMESTAMPDIFF(day, from_date, to_date) as days FROM dept_emp AS dept_emp INNER JOIN employees AS employees ON dept_emp.emp_no = employees.emp_no INNER JOIN departments AS departments ON dept_emp.dept_no = departments.dept_no WHERE dept_emp.dept_no = 'd005' ORDER BY from_date ASC, to_date DESC limit 10
