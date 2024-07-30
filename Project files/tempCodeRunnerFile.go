package main

import "fmt"

func main() {
    // Type inference with a variable declaration
    var age = 30
    fmt.Printf("Age is of type %T\n", age) // %T prints the type of a variable

    // Type inference with a short variable declaration
    name := "John Doe"
    fmt.Printf("Name is of type %T\n", name)

    // Type inference in a function return type
    result := add(10, 20)
    fmt.Printf("Result is of type %T\n", result)
}

// Function with type inference in the return type
func add(a, b int) int {
    return a + b
}