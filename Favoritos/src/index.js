const express = require('express');
const productosController = require('./controllers/favoritosController');
const morgan = require('morgan'); 
const app = express();
app.use(morgan('dev'));
app.use(express.json());

app.use(productosController);

app.listen(3002, () => {
  console.log('Microservicio Favoritos ejecutandose en el puerto 3002');
});