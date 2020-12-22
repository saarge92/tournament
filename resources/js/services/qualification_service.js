/**
 * Удаленный запрос на генерацию турнирной таблицы по дивизионам
 */
export async function generateTournamentsData() {
    const response = await axios.post('api/qualification/generate').catch(console.log)
    return response.data
}
