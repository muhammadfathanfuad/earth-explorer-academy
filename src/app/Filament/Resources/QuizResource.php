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
                Forms\Components\Select::make('topic_id')
                    ->relationship('topic', 'title')
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label('Tipe Soal')
                    ->options([
                        'multiple_choice' => 'Pilihan Ganda',
                        'true_false' => 'Benar / Salah (Geser Kartu)',
                        'sequence' => 'Puzzle Urutan (Susun Langkah)',
                    ])
                    ->required()
                    ->live()
                    ->default('multiple_choice'),

                Forms\Components\Textarea::make('question')
                    ->required()
                    ->label('Pertanyaan / Instruksi')
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('quizzes'),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('option_a')
                            ->label(fn (Get $get) => $get('type') === 'sequence' ? 'Langkah 1 (Pertama)' : 'Opsi A')
                            ->visible(fn (Get $get) => in_array($get('type'), ['multiple_choice', 'sequence']))
                            ->required(fn (Get $get) => in_array($get('type'), ['multiple_choice', 'sequence'])),

                        Forms\Components\TextInput::make('option_b')
                            ->label(fn (Get $get) => $get('type') === 'sequence' ? 'Langkah 2' : 'Opsi B')
                            ->visible(fn (Get $get) => in_array($get('type'), ['multiple_choice', 'sequence']))
                            ->required(fn (Get $get) => in_array($get('type'), ['multiple_choice', 'sequence'])),

                        Forms\Components\TextInput::make('option_c')
                            ->label(fn (Get $get) => $get('type') === 'sequence' ? 'Langkah 3' : 'Opsi C')
                            ->visible(fn (Get $get) => in_array($get('type'), ['multiple_choice', 'sequence']))
                            ->required(fn (Get $get) => $get('type') === 'multiple_choice' || $get('type') === 'sequence'),


                        Forms\Components\TextInput::make('option_d')
                            ->label(fn (Get $get) => $get('type') === 'sequence' ? 'Langkah 4 (Terakhir)' : 'Opsi D')
                            ->visible(fn (Get $get) => in_array($get('type'), ['multiple_choice', 'sequence']))
                            ->required(fn (Get $get) => $get('type') === 'multiple_choice' || $get('type') === 'sequence'),
                    ]),

                // Field gambar untuk setiap opsi (hanya muncul untuk sequence)
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\FileUpload::make('option_a_image')
                            ->label('Gambar Langkah 1')
                            ->image()
                            ->directory('quizzes/options')
                            ->visible(fn (Get $get) => $get('type') === 'sequence')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Upload gambar untuk langkah pertama (opsional)'),

                        Forms\Components\FileUpload::make('option_b_image')
                            ->label('Gambar Langkah 2')
                            ->image()
                            ->directory('quizzes/options')
                            ->visible(fn (Get $get) => $get('type') === 'sequence')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Upload gambar untuk langkah kedua (opsional)'),

                        Forms\Components\FileUpload::make('option_c_image')
                            ->label('Gambar Langkah 3')
                            ->image()
                            ->directory('quizzes/options')
                            ->visible(fn (Get $get) => $get('type') === 'sequence')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Upload gambar untuk langkah ketiga (opsional)'),

                        Forms\Components\FileUpload::make('option_d_image')
                            ->label('Gambar Langkah 4')
                            ->image()
                            ->directory('quizzes/options')
                            ->visible(fn (Get $get) => $get('type') === 'sequence')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Upload gambar untuk langkah keempat (opsional)'),
                    ])
                    ->visible(fn (Get $get) => $get('type') === 'sequence'),

                Forms\Components\Select::make('correct_answer')
                    ->label('Kunci Jawaban')
                    ->options(fn (Get $get) => match ($get('type')) {
                        'true_false' => ['true' => 'Benar', 'false' => 'Salah'],
                        'multiple_choice' => ['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'],
                        default => [],
                    })
                    ->visible(fn (Get $get) => in_array($get('type'), ['multiple_choice', 'true_false']))
                    ->required(fn (Get $get) => in_array($get('type'), ['multiple_choice', 'true_false'])),
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
