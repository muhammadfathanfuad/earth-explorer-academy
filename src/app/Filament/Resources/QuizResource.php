<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Buat Soal Game')
                    ->schema([
                        Forms\Components\Select::make('topic_id')
                            ->relationship('topic', 'title')
                            ->label('Topik Materi')
                            ->required(),

                        // --- 1. PILIH TIPE GAME ---
                        Forms\Components\Select::make('type')
                            ->label('Tipe Permainan')
                            ->options([
                                'multiple_choice' => 'Pilihan Ganda (ABCD)',
                                'true_false' => 'Mitos vs Fakta (Swipe)',
                            ])
                            ->default('multiple_choice')
                            ->live() // KUNCI REAKTIF: Agar form dibawahnya berubah realtime
                            ->afterStateUpdated(fn(callable $set) => $set('correct_answer', null)) // Reset jawaban kalau ganti tipe
                            ->required(),

                        // --- 2. PERTANYAAN ---
                        Forms\Components\Textarea::make('question')
                            ->label(fn(Get $get) => $get('type') === 'true_false' ? 'Pernyataan (Mitos/Fakta)' : 'Pertanyaan Soal')
                            ->required()
                            ->columnSpanFull(),

                        // --- 3. INPUT KHUSUS PILIHAN GANDA ---
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('option_a')->label('Opsi A')->required(),
                                Forms\Components\TextInput::make('option_b')->label('Opsi B')->required(),
                                Forms\Components\TextInput::make('option_c')->label('Opsi C')->required(),
                                Forms\Components\TextInput::make('option_d')->label('Opsi D')->required(),
                            ])
                            // Hanya muncul jika tipe = multiple_choice
                            ->visible(fn(Get $get) => $get('type') === 'multiple_choice'),

                        // ... (Input Tipe Soal & Topik di atasnya) ...

                    
                    // ... (lanjutan kode Textarea question yang lama) ...
                        // --- 4. KUNCI JAWABAN (DINAMIS) ---
                        Forms\Components\Select::make('correct_answer')
                            ->label('Kunci Jawaban')
                            ->options(function (Get $get) {
                                // Jika Pilihan Ganda, opsinya A/B/C/D
                                if ($get('type') === 'multiple_choice') {
                                    return [
                                        'a' => 'A',
                                        'b' => 'B',
                                        'c' => 'C',
                                        'd' => 'D',
                                    ];
                                }
                                // Jika Mitos/Fakta, opsinya Fakta/Mitos
                                return [
                                    'true' => 'FAKTA (Benar)',
                                    'false' => 'MITOS (Salah)',
                                ];
                            })
                            ->required(),

                            // --- 5. PERTANYAAN & GAMBAR ---
                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar Ilustrasi Soal (Opsional)')
                            ->image() // Validasi harus gambar
                            ->directory('quiz-images') // Simpan di folder storage/app/public/quiz-images
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('explanation')
                            ->label('Penjelasan Ilmiah (Muncul setelah menjawab)')
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('topic_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('option_a')
                    ->searchable(),
                Tables\Columns\TextColumn::make('option_b')
                    ->searchable(),
                Tables\Columns\TextColumn::make('option_c')
                    ->searchable(),
                Tables\Columns\TextColumn::make('option_d')
                    ->searchable(),
                Tables\Columns\TextColumn::make('correct_answer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
}
