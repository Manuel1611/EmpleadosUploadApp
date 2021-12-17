# EmpleadosUploadApp
 
Esta aplicación toma como base la que hicimos de gestión de empleados, con departamentos y puestos.

He implementado que cada empleado pueda tener una imagen asociada.

Cada empleado SOLO puede tener una imagen en mi proyecto, que podría ser una foto suya, por ejemplo;
igual que otras aplicaciones, donde puedes tener una única foto, que sería como tu foto de perfil.

La imagen la guardo en el storage de C9, y en la base de datos guardo otra información relevante como
el mimeType, o el nombre que se le ha dado a la imagen.

He implementado que se pueda editar la imagen, así como el nombre de la misma.

Cada vez que se edita una imagen, se borra la anterior del storage para que sólo haya una foto por
empleado, y también se actualiza con los nuevos datos en la base de datos.

Las imágenes de los empleados se pueden borrar en el edit del empleado, y también se borran automáticamente
al eliminar a un empleado. Se borra tanto de la base de datos como del Storage.
