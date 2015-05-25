(in-package :nlpes)

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

(defun split-string (str &optional (delim #\space))
  (loop for i across (format nil "~a~a" str delim)
	with word = nil
	with result = nil
	if (eql i delim)
	do (push (coerce (reverse word) 'string) result)
	   (setf word nil)
	else
	do (push i word)
	finally (return (reverse result))))

(defun last1 (lst)
	(car (last lst)))	
	
	
(defun single (lst)
	(and (consp lst) (not (cdr lst))))	
	
;;Nos da el ultimo atomo de cualquier lista	
(defun last-atom (llist)
	(loop with l = llist 
		while (consp l)
		do (setq l (last1 l))
		finally (return l)))

(defun first-atom (llist)
	(loop with l = llist 
		while (consp l)
		do (setq l (car l))
		finally (return  l)))

		

