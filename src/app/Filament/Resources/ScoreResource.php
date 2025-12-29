<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScoreResource\Pages;
use App\Models\Score;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class ScoreResource extends Resource
{
    protected static ?string $model = Score::class;

    // Ganti Icon Menu (Piala)
    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    // Label Menu
    protected static ?string $navigationLabel = 'Peringkat & Skor';
    protected static ?string $navigationGroup = 'Manajemen Siswa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Skor')
                    ->schema([
                        // 1. Pilih Siswa (Bisa Edit Peserta)
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Nama Peserta')
                            ->searchable()
                            ->preload()
                            ->required(),

                        // 2. Pilih Topik Kuis
                        Forms\Components\Select::make('topic_id')
                            ->relationship('topic', 'title')
                            ->label('Topik Kuis')
                            ->required(),

                        // 3. Input Skor
                        Forms\Components\TextInput::make('score')
                            ->label('Poin Skor')
                            ->numeric()
                            ->required()
                            ->step(10),

                        // 4. Atur Tanggal (Bisa "Kemarin", "Besok", dll)
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Tanggal Tercatat')
                            ->required()
                            ->default(now()),

                        Forms\Components\Toggle::make('is_visible')
                            ->label('Tampilkan di Website?')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom Nama
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // Kolom Topik
                Tables\Columns\TextColumn::make('topic.title')
                    ->label('Topik')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                // Kolom Skor
                Tables\Columns\TextColumn::make('score')
                    ->label('Poin')
                    ->sortable()
                    ->color(fn(string $state): string => $state >= 80 ? 'success' : ($state >= 50 ? 'warning' : 'danger')),

                // Kolom Tanggal
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Main')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_visible')
                    ->label('Tampil?')
                    ->onColor('success')
                    ->offColor('danger')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc') // Yang terbaru paling atas
            ->filters([
                // --- FILTER CANGGIH: PILIH TANGGAL ---
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('date_from')->label('Dari Tanggal'),
                        DatePicker::make('date_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                // Tombol Edit
                Tables\Actions\EditAction::make(),
                // Tombol Hapus
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Hapus Banyak Sekaligus
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScores::route('/'),
            'create' => Pages\CreateScore::route('/create'),
            'edit' => Pages\EditScore::route('/{record}/edit'),
        ];
    }
}
