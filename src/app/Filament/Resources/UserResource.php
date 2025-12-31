<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Tambahkan ini untuk helper text

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Daftar Kapten';
    protected static ?string $modelLabel = 'Kapten (Siswa)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Kapten')
                    ->description('Isi nama saja untuk siswa. Email & Password hanya untuk Admin.')
                    ->schema([
                        
                        // 1. NAMA (WAJIB)
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Panggilan')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true) // Agar sistem bisa membaca nama saat diketik
                            ->helperText('Nama ini yang digunakan siswa untuk login.'),

                        // 2. EMAIL (OPSIONAL UNTUK SISWA)
                        Forms\Components\TextInput::make('email')
                            ->label('Email (Khusus Admin)')
                            ->email()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Kosongkan untuk siswa...')
                            ->helperText('Wajib diisi jika user ini adalah ADMIN/GURU.')
                            // --- MAGIC LOGIC DI SINI ---
                            // Saat disimpan, cek isinya:
                            // Jika ada isi -> Simpan email tersebut (Admin)
                            // Jika KOSONG -> Buat email dummy dari nama (Siswa)
                            ->dehydrateStateUsing(function ($state, Forms\Get $get) {
                                if (filled($state)) {
                                    return $state; // Kalau admin isi email, pakai itu
                                }
                                // Kalau kosong, generate: nama-acak@siswa.com
                                $slug = Str::slug($get('name'));
                                return $slug . '-' . rand(1000, 9999) . '@siswa.com';
                            }),

                        // 3. PASSWORD (OPSIONAL)
                        Forms\Components\TextInput::make('password')
                            ->label('Password (Khusus Admin)')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state ?? 'rahasia'))
                            ->dehydrated(fn ($state) => filled($state) || request()->routeIs('filament.*.create'))
                            ->helperText('Biarkan kosong untuk siswa (Default: rahasia).'),
                            
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kapten')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // Kita tampilkan Role/Status berdasarkan emailnya
                Tables\Columns\TextColumn::make('email')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => str_contains($state, '@siswa.com') ? 'info' : 'success')
                    ->formatStateUsing(fn (string $state): string => str_contains($state, '@siswa.com') ? 'SISWA' : 'ADMIN'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}