<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopicResource\Pages;
use App\Filament\Resources\TopicResource\RelationManagers;
use App\Models\Topic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;

class TopicResource extends Resource
{
    protected static ?string $model = Topic::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            // --- BAGIAN 1: INFO UTAMA (COVER & JUDUL) ---
            Forms\Components\Section::make('Informasi Utama')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->label('Judul Bab')
                        ->live()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                    
                    Forms\Components\TextInput::make('slug')->required()->readOnly(),
                    Forms\Components\TextInput::make('summary')->label('Ringkasan Pendek')->required(),
                    
                    // Ini Gambar Cover Utama (Halaman Depan)
                    Forms\Components\FileUpload::make('image')
                        ->label('Gambar Cover (Halaman Utama)')
                        ->image()
                        ->directory('topics'),
                ]),

            // --- BAGIAN 2: PENGATURAN SLIDE (REPEATER) ---
            Forms\Components\Section::make('Isi Materi (Slide)')
                ->schema([
                    Repeater::make('content') // Kolom database 'content'
                        ->label('Daftar Slide')
                        ->schema([
                            // Input Gambar Slide
                            Forms\Components\FileUpload::make('slide_image')
                                ->label('Gambar Ilustrasi Slide')
                                ->image()
                                ->directory('slides')
                                ->required(),

                            // Input Teks Slide
                            Forms\Components\RichEditor::make('slide_text')
                                ->label('Teks Penjelasan')
                                ->required()
                                ->toolbarButtons([
                                    'bold', 'italic', 'underline', 'bulletList', 'orderedList',
                                ]),
                        ])
                        ->collapsible() // Biar bisa dilipat
                        ->itemLabel(fn (array $state): ?string => 'Slide: ' . strip_tags($state['slide_text'] ?? 'Slide Baru')),
                ])
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
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
            'index' => Pages\ListTopics::route('/'),
            'create' => Pages\CreateTopic::route('/create'),
            'edit' => Pages\EditTopic::route('/{record}/edit'),
        ];
    }
}
