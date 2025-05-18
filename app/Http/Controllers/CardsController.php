<?php

namespace App\Http\Controllers;

use \App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardsController extends Controller
{
    //
    public function index()
    {
        // Logic to retrieve and return all cards

        // Not json
        return view('cards.index', [
            'cards' => Card::all()
        ]);
    }
    public function show($id)
    {
        // Logic to retrieve and return a specific card by ID

        $card = Card::findOrFail($id);
        return view('cards.show', [
            'card' => $card
        ]);
    }
    public function create()
    {
        // Logic to show the form for creating a new card

        return view('cards.create');
    }
    public function store(Request $request)
    {
        // Logic to store a new card

        $card = new Card();
        $card->user_id = Auth::user()->id; // Assuming user is authenticated
        $card->module = $request->input('module');
        $card->topic = $request->input('topic');
        $card->question = $request->input('question');
        $card->answer = $request->input('answer');
        $card->category = $request->input('category');
        $card->difficulty = $request->input('difficulty');
        $card->is_correct = $request->input('is_correct', false);
        $card->is_bookmarked = $request->input('is_bookmarked', false);
        $card->save();

        return redirect()->route('cards.index')->with('success', 'Card created successfully.');
    }
    public function edit($id)
    {
        // Logic to show the form for editing a specific card

        $card = Card::findOrFail($id);
        return view('cards.edit', [
            'card' => $card
        ]);
    }
    public function update(Request $request, $id)
    {
        // Logic to update a specific card

        $card = Card::findOrFail($id);
        $card->module = $request->input('module');
        $card->topic = $request->input('topic');
        $card->question = $request->input('question');
        $card->answer = $request->input('answer');
        $card->category = $request->input('category');
        $card->difficulty = $request->input('difficulty');
        $card->is_correct = $request->input('is_correct', false);
        $card->is_bookmarked = $request->input('is_bookmarked', false);
        $card->save();

        return redirect()->route('cards.index')->with('success', 'Card updated successfully.');
    }
    public function destroy($id)
    {
        // Logic to delete a specific card

        $card = Card::findOrFail($id);
        $card->delete();

        return redirect()->route('cards.index')->with('success', 'Card deleted successfully.');
    }
    public function bookmark($id)
    {
        // Logic to bookmark a specific card

        $card = Card::findOrFail($id);
        $card->is_bookmarked = !$card->is_bookmarked; // Toggle bookmark status
        $card->save();

        return redirect()->route('cards.index')->with('success', 'Card bookmark status updated successfully.');
    }
    public function correct($id)
    {
        // Logic to mark a specific card as correct

        $card = Card::findOrFail($id);
        $card->is_correct = !$card->is_correct; // Toggle correct status
        $card->save();

        return redirect()->route('cards.index')->with('success', 'Card correctness status updated successfully.');
    }
    public function search(Request $request)
    {
        // Logic to search for cards based on user input

        $query = $request->input('query');
        $cards = Card::where('question', 'like', '%' . $query . '%')
            ->orWhere('answer', 'like', '%' . $query . '%')
            ->get();

        return view('cards.search', [
            'cards' => $cards,
            'query' => $query
        ]);
    }
    public function filter(Request $request)
    {
        // Logic to filter cards based on user-selected criteria

        $category = $request->input('category');
        $difficulty = $request->input('difficulty');
        $cards = Card::query();

        if ($category) {
            $cards->where('category', $category);
        }
        if ($difficulty) {
            $cards->where('difficulty', $difficulty);
        }

        return view('cards.filter', [
            'cards' => $cards->get(),
            'category' => $category,
            'difficulty' => $difficulty
        ]);
    }
    public function statistics()
    {
        // Logic to display statistics about cards

        $totalCards = Card::count();
        $correctCards = Card::where('is_correct', true)->count();
        $bookmarkedCards = Card::where('is_bookmarked', true)->count();

        return view('cards.statistics', [
            'totalCards' => $totalCards,
            'correctCards' => $correctCards,
            'bookmarkedCards' => $bookmarkedCards
        ]);
    }
    // import using a CSV file
    public function import(Request $request)
    {
        // Logic to import cards from a CSV file

        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $data = array_map('str_getcsv', file($file->getRealPath()));

            foreach ($data as $row) {
                Card::create([
                    'user_id' => Auth::user()->id,
                    'module' => $row[0],
                    'topic' => $row[1],
                    'question' => $row[2],
                    'answer' => $row[3],
                    'category' => $row[4],
                    'difficulty' => $row[5],
                    'is_correct' => filter_var($row[6], FILTER_VALIDATE_BOOLEAN),
                    'is_bookmarked' => filter_var($row[7], FILTER_VALIDATE_BOOLEAN),
                ]);
            }

            return redirect()->route('cards.index')->with('success', 'Cards imported successfully.');
        }

        return redirect()->back()->withErrors(['csv_file' => 'Please upload a valid CSV file.']);
    }
    public function export()
    {
        // Logic to export cards to a CSV file
        $cards = Card::all();
        $filename = 'cards_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($cards) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Module', 'Topic', 'Question', 'Answer', 'Category', 'Difficulty', 'Is Correct', 'Is Bookmarked']);

            foreach ($cards as $card) {
                fputcsv($handle, [
                    $card->module,
                    $card->topic,
                    $card->question,
                    $card->answer,
                    $card->category,
                    $card->difficulty,
                    $card->is_correct ? 'Yes' : 'No',
                    $card->is_bookmarked ? 'Yes' : 'No',
                ]);
            }
            
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function random()
    {
        // Logic to retrieve a random card

        $card = Card::inRandomOrder()->first();
        return view('cards.random', [
            'card' => $card
        ]);
    }
    
    public function bulkDelete(Request $request)
    {
        // Logic to delete multiple cards at once
        
        $validated = $request->validate([
            'card_ids' => 'required|array',
            'card_ids.*' => 'exists:cards,id',
        ]);
        
        Card::whereIn('id', $request->input('card_ids'))->delete();
        
        return redirect()->route('cards.index')->with('success', 'Selected cards deleted successfully.');
    }
}
