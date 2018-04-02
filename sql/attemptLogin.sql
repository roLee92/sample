/* Select customers where the username and password match those passed as parameters */
SELECT *
FROM Customers
WHERE
	username = :username AND
	password = :password
	