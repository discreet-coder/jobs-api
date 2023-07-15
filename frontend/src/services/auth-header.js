export default function authHeader() {
  let token = localStorage.getItem('token');

  if (token && token != '') {
    return { Authorization: 'Bearer ' + token };
  } else {
    return {};
  }
}
