<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test to show all books.
     *
     * @return void
     */
    public function test_can_get_all_books()
    {
    
        $book1 = Book::factory()->create([
            'book_title' => 'Sample Book 1',
            'authors' => 'Sample Author(s)',
            'book_description' => 'Sample book description',
            'link' => 'Book link',
            'image_url' => 'Book image URL'
        ]);

        $book2 = Book::factory()->create([
            'book_title' => 'Sample Book 2',
            'authors' => 'Sample Author(s)',
            'book_description' => 'Sample book description',
            'link' => 'Book link',
            'image_url' => 'Book image URL'
        ]);

        $book3 = Book::factory()->create([
            'book_title' => 'Sample Book 3',
            'authors' => 'Sample Author(s)',
            'book_description' => 'Sample book description',
            'link' => 'Book link',
            'image_url' => 'Book image URL'
        ]);

        $response = $this->get('/books');
        $response->assertStatus(200);

        $response->assertJson([
            [
                'book_title' => 'Sample Book 1',
                'authors' => 'Sample Author(s)',
                'book_description' => 'Sample book description',
                'link' => 'Book link',
                'image_url' => 'Book image URL'
            ],
            [
                'book_title' => 'Sample Book 2',
                'authors' => 'Sample Author(s)',
                'book_description' => 'Sample book description',
                'link' => 'Book link',
                'image_url' => 'Book image URL'
            ],
            [
                'book_title' => 'Sample Book 3',
                'authors' => 'Sample Author(s)',
                'book_description' => 'Sample book description',
                'link' => 'Book link',
                'image_url' => 'Book image URL'
            ]
        ]);
    }

    /**
     * Test case for the store method.
     *
     * @return void
     */
    public function testStore()
    {
        $response = $this->post('/books', [
            'book_title' => 'Sample Book',
            'authors' => 'Sample Author(s)',
            'book_description' => 'Sample book description',
            'link' => 'Book link',
            'image_url' => 'Book image URL'
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'book_title' => 'Sample Book',
            'authors' => 'Sample Author(s)',
            'book_description' => 'Sample book description',
            'link' => 'Book link',
            'image_url' => 'Book image URL'
        ]);
    }


    /**
     * Test to destroy.
     *
     * @return void
     */
    public function testDestroy($id)
    {
        
        $book = factory(Book::class)->create();
        
        $response = $this->delete('/books/' . $book->id);
        $response->assertStatus(204);
        
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
