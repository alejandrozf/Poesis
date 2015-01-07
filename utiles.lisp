;;Funciones y macros utiles
(defvar *pass* nil) 
(defun pass ()
  (prog1 
      *pass*
    (setq *pass* nil)))

(defun mark-to-pass ()
  (setq *pass* t))

(defmacro [( t-array  i ])
    `(aref ,t-array ,i))

;;Funcion para indexar el proximo elemento del indice i en una palabra
;;(retorna nil si se va de rango)
(defun next-char (palabra i)
  (if (<= 0 (1+ i) (1- (length palabra)))
	    (elt palabra (1+ i))))

;;Un 'append + generalizado que toma tambien atomos 
(defun append-all (head &rest tail)
  (cons head 
	(apply #'append 
	       (mapcar (lambda (c) 
			 (if (listp c) c (list c)))
		       tail))))	
