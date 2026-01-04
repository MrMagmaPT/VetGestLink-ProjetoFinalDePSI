-- =====================================================
-- DADOS INICIAIS
-- =====================================================

-- Inserir método de pagamento padrão
INSERT INTO `metodospagamentos` (`nome`, `vigor`, `eliminado`) VALUES
('Dinheiro', 1, 0),
('Multibanco', 1, 0),
('MBWay', 1, 0),
('Cartão de Crédito', 1, 0);

-- Inserir categorias de medicamentos
INSERT INTO `categorias` (`nome`, `eliminado`) VALUES
('Antibióticos', 0),
('Anti-inflamatórios', 0),
('Antiparasitários', 0),
('Vacinas', 0),
('Suplementos', 0),
('Analgésicos', 0);

-- Inserir espécies comuns
INSERT INTO `especies` (`nome`, `eliminado`) VALUES
('Cão', 0),
('Gato', 0),
('Ave', 0),
('Coelho', 0),
('Roedor', 0);

-- Inserir raças comuns de cães (especie_id = 1)
INSERT INTO `racas` (`nome`, `especies_id`, `eliminado`) VALUES
('Labrador', 1, 0),
('Golden Retriever', 1, 0),
('Pastor Alemão', 1, 0),
('Bulldog', 1, 0),
('Beagle', 1, 0),
('Poodle', 1, 0),
('Yorkshire', 1, 0),
('Chihuahua', 1, 0),
('Sem Raça Definida', 1, 0);

-- Inserir raças comuns de gatos (especie_id = 2)
INSERT INTO `racas` (`nome`, `especies_id`, `eliminado`) VALUES
('Persa', 2, 0),
('Siamês', 2, 0),
('Maine Coon', 2, 0),
('British Shorthair', 2, 0),
('Comum Europeu', 2, 0),
('Sem Raça Definida', 2, 0);

-- Inserir serviços básicos
INSERT INTO `servicos` (`nome`, `valor`, `eliminado`) VALUES
('Consulta Geral', 35.00, 0),
('Vacinação', 25.00, 0),
('Desparasitação', 15.00, 0),
('Cirurgia Simples', 150.00, 0),
('Cirurgia Complexa', 350.00, 0),
('Análises Clínicas', 50.00, 0),
('Radiografia', 40.00, 0),
('Ecografia', 60.00, 0),
('Limpeza Dentária', 80.00, 0),
('Internamento (por dia)', 45.00, 0);