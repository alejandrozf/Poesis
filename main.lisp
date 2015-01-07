;;Programa principal
(load "silabas.lisp")
(load "metrica.lisp")
(load "rima.lisp")

(defparameter *programa* "Poesis")
(defparameter *version* "alpha")
(defparameter *licencia* "BSD")
(defparameter *mensaje* "<...>")
(defparameter *autor* "Alejandro Zamora Fonseca")
(defparameter *email* "ale2014.zamora@gmail.com")
    
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

(defun prompt () ;falta
  (prin1 "Inserte una opción según el siguiente menú:")
  )

(defun selection () ;falta
  )

(defun main ()
  (bienvenida)
  (prompt)
  (loop named global
       (cond ((zerop (selection)) (print "Final!") (return-from global "Ha cerrado el programa."))
	     (t (print "ciclo...")) ;chequear las diferentes opciones
	     )))

(main)