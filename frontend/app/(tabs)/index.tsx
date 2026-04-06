import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, TextInput, ScrollView, TouchableOpacity } from 'react-native';

export default function App() {
  // Estados para os inputs (As tigelas da cozinha)
  const [preco, setPreco] = useState('');
  const [pesoCompra, setPesoCompra] = useState('');
  const [usoReceita, setUsoReceita] = useState('');

  // Estados para os resultados (O que o Backend calcula)
  const [custoUso, setCustoUso] = useState('0.00');
  const [precoGrama, setPrecoGrama] = useState('0.00');

  // Lógica de Cálculo (A espinha dorsal)
  useEffect(() => {
    const p = parseFloat(preco) || 0;
    const pt = parseFloat(pesoCompra) || 0;
    const u = parseFloat(usoReceita) || 0;

    if (p > 0 && pt > 0) {
      // 1. Quanto custa cada graminha (Sua nova ideia!)
      const valorGrama = p / pt;
      setPrecoGrama(valorGrama.toFixed(4)); // 4 casas para precisão

      // 2. Quanto custa o que foi usado (O 0.63)
      const resultadoUso = valorGrama * u;
      setCustoUso(resultadoUso.toFixed(2));
    }
  }, [preco, pesoCompra, usoReceita]);

  return (
    <ScrollView contentContainerStyle={styles.container}>
      <Text style={styles.titulo}>🍰 Master Bakery Mobile</Text>
      
      <View style={styles.card}>
        <Text style={styles.label}>Preço Pago no Pacote (R$):</Text>
        <TextInput style={styles.input} keyboardType="numeric" placeholder="Ex: 5.00" onChangeText={setPreco} />

        <Text style={styles.label}>Peso do Pacote Todo (g):</Text>
        <TextInput style={styles.input} keyboardType="numeric" placeholder="Ex: 1000" onChangeText={setPesoCompra} />

        <Text style={styles.label}>Quanto usou na Receita (g):</Text>
        <TextInput style={styles.input} keyboardType="numeric" placeholder="Ex: 250" onChangeText={setUsoReceita} />

        {/* Sua ideia das duas colunas de custo: */}
        <View style={styles.gridResultado}>
          <View style={styles.coluna}>
            <Text style={styles.subtitulo}>Custo da Grama</Text>
            <Text style={styles.valorDestaque}>R$ {precoGrama}</Text>
          </View>
          <View style={styles.coluna}>
            <Text style={styles.subtitulo}>Custo no Bolo</Text>
            <Text style={styles.valorDestaque}>R$ {custoUso}</Text>
          </View>
        </View>

        <TouchableOpacity style={styles.botao} onPress={() => alert('Amanhã conectamos ao SQL!')}>
          <Text style={styles.textoBotao}>Salvar no SQL</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flexGrow: 1, backgroundColor: '#fff5f8', padding: 20, alignItems: 'center' },
  titulo: { fontSize: 26, color: '#d81b60', fontWeight: 'bold', marginTop: 40, marginBottom: 20 },
  card: { backgroundColor: 'white', width: '100%', borderRadius: 15, padding: 20, elevation: 4 },
  label: { fontSize: 14, color: '#666', marginBottom: 5 },
  input: { borderBottomWidth: 1, borderBottomColor: '#ff69b4', marginBottom: 20, padding: 8, fontSize: 18 },
  gridResultado: { flexDirection: 'row', justifyContent: 'space-between', marginVertical: 20, borderTopWidth: 1, borderTopColor: '#eee', paddingTop: 20 },
  coluna: { alignItems: 'center', width: '48%' },
  subtitulo: { fontSize: 12, color: '#888', textTransform: 'uppercase' },
  valorDestaque: { fontSize: 22, color: '#2e7d32', fontWeight: 'bold' },
  botao: { backgroundColor: '#ff69b4', padding: 15, borderRadius: 10, alignItems: 'center', marginTop: 10 },
  textoBotao: { color: 'white', fontWeight: 'bold', fontSize: 16 }
});