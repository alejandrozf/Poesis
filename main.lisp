;;Para tener una CLI

;;Programa principal
(load "silabas.lisp")
(load "metrica.lisp")
(load "rima.lisp")
(load "acentuacion.lisp")


(defparameter *programa* "Poesis")
(defparameter *version* "alpha")
(defparameter *licencia* "MIT")
(defparameter *mensaje* "<...>")
(defparameter *autor* "Alejandro Zamora Fonseca")
(defparameter *email* "ale2014.zamora@gmail.com")

#+sbcl
(setf SB-IMPL::*DEFAULT-EXTERNAL-FORMAT* :UTF-8)
    
(defun bienvenida ()
  (format t "Este es ~a en su versión ~a.
          ~a.
          ~a se distribuye bajo licencia ~a.
          Cualquier tipo de feedback es bienvenido, por favor escribir al autor ~a a: <~a>."
	  *programa*
	  *version*
	  *mensaje*
	  *programa*
	  *licencia*
	  *autor*
	  *email*))

(defun prompt () 
  (princ "Inserte una opción según el siguiente menú
		   Opciones> Escriba el valor numérico de la opción + <Entrar> y
           Opciones> luego los demás parámetros según lo que especificado a continuación. 
		   0- Cierra el programa.
		   1- Para dividir en sílabas, teclee después <espacio> y la <palabra> a dividir.
		   2- Para determinar el tipo de acentuación de una palabra,teclee <espacio> y <palabra>.
		   3- Para determinar las sílabas métricas de una oración(verso), inserte una lista de palabras 
		   separadas por <espacio>
           4- Para determinar si dos palabras riman, teclee:
			<espacio>  <tipo_de_rima> <espacio> <palabra1> <espacio> <palabra2>
		   Nota: <tipo_de_rima> = asonante|consonante.
		   -------------------
		   "))

(defun procesar-seleccion () 
  (princ "
		  Inserte su opción: ")
  (let* ((raw_text (read-from-string (format nil "(~a)" (read-line))))
	     (opt (car raw_text))
	     (text (mapcar #'symbol-name (cdr raw_text))))
	(cond ((eql opt 0) nil)
		  ((eql opt 1) (princ (silabas->cadena (car text))))
		  ((eql opt 2) (princ (acentuacion (car text))))
		  ((eql opt 3) (princ (silabas-metricas text )))
		  ((eql opt 4) (princ "RIMA AUN NO COMPLETA"))
		  (t  (princ "Opción no válida")))))
		 

(defun main ()
  (bienvenida)
  (prompt)
  (loop named global
      while t
	  do (or (procesar-seleccion) (return-from global "Ha cerrado el programa"))))
