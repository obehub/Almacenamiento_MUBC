fetch('PHP/test.php')
  .then(r => r.json())
  .then(console.log)
  .catch(console.error);
