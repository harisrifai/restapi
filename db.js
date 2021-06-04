var mysql = require('mysql');

var connection = mysql.createConnection({
  host: 'localhost',
  user: 'newpos',
  password: 'newpos123',
  database: 'myapp'
});

connection.connect(function(err) {
  if (err) throw err;
  console.log('connected!');
});

module.exports = connection;
