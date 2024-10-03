<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use App\Models\Tasks;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TaskResource extends Resource
{
    protected static ?string $model = Tasks::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    protected static ?string $label = "Masalalar";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Masala nomi')
                    ->columnSpanFull()
                    ->nullable(),

                Forms\Components\TextInput::make('time')
                    ->label('Vaqt')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('memory')
                    ->label('Xotira')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('difficulty')
                    ->label('Qiyinchilik')
                    ->numeric()
                    ->default(0),
                Forms\Components\RichEditor::make('condition')
                    ->label('Sharti')
                    ->nullable()
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('input')
                    ->label('Kiruvchi ma\'lumotlar')
                    ->nullable(),
                Forms\Components\Textarea::make('output')
                    ->label('Chiquvchi ma\'lumotlar')
                    ->nullable(),
                Forms\Components\RichEditor::make('note')
                    ->label('Izoh')
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => Auth::id()),

                Forms\Components\Repeater::make('task_inputs_outputs')
                    ->label('Misollar')
                    ->relationship('sample')
                    ->schema([
                        Forms\Components\Textarea::make('input')
                            ->label('Kiruvchi ma\'lumotlar')
                            ->nullable(),

                        Forms\Components\Textarea::make('output')
                            ->label('Chiquvchi ma\'lumotlar')
                            ->nullable(),
                    ])
                    ->minItems(1)
                    ->createItemButtonLabel('Qo\'shish')
                    ->cloneable()
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Masala nomi'),
                Tables\Columns\TextColumn::make('time')->label('Vaqt'),
                Tables\Columns\TextColumn::make('memory')->label('Xotira'),
                Tables\Columns\TextColumn::make('difficulty')->label('Qiyinchilik'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
