<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
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
                    ->label('Pilih Bab Materi')
                    ->required(),

                Forms\Components\Textarea::make('question')
                    ->label('Pertanyaan')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('option_a')->required()->label('Opsi A'),
                Forms\Components\TextInput::make('option_b')->required()->label('Opsi B'),
                Forms\Components\TextInput::make('option_c')->required()->label('Opsi C'),
                Forms\Components\TextInput::make('option_d')->required()->label('Opsi D'),

                Forms\Components\Select::make('correct_answer')
                    ->options([
                        'a' => 'A',
                        'b' => 'B',
                        'c' => 'C',
                        'd' => 'D',
                    ])
                    ->label('Kunci Jawaban')
                    ->required(),
                
                Forms\Components\Textarea::make('explanation')
                    ->label('Pembahasan (Muncul setelah jawab)')
                    ->columnSpanFull(),
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
